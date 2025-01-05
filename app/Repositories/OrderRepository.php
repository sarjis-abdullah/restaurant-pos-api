<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Enums\TableStatus;
use App\Events\OrderCreatedEvent;
use App\Exceptions\PosException;
use App\Models\Addon;
use App\Models\AddonVariant;
use App\Models\Discount;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDiscount;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Stock;
use App\Models\Table;
use App\Models\Tax;
use App\Models\Variant;
use App\Repositories\Contracts\OrderInterface;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class OrderRepository extends BaseRepository implements OrderInterface
{
    /**
     * @throws \Exception
     */
    public function saveOrder(array $orderData)
    {
        DB::beginTransaction();

        try {
            // Step 1: Calculate the order
            $calculatedOrder = $this->calculateOrder($orderData['orders']);
            $hasDiscounts = isset($orderData['discounts']) && count($orderData['discounts']) > 0;

            $finalTotal = $calculatedOrder['finalTotal'];
            $finalTotalTax = $calculatedOrder['finalTotalTax'];
            $finalTotalDiscount = $hasDiscounts ? $this->getAllDiscountSum($orderData['discounts'], $calculatedOrder['finalTotalDiscount']) : $calculatedOrder['finalTotalDiscount'];
            $finalTotal -= $finalTotalDiscount;
            $finalTotalAddons = $calculatedOrder['finalTotalAddons'];
            if (isset($orderData['table_id'])){
                Table::find($orderData['table_id'])->update([
                    'status' => TableStatus::booked
                ]);
            }
            $order = Order::create([
                'total_amount' => $finalTotal,
                'tax' => $finalTotalTax,
                'discount' => $finalTotalDiscount,
                'addons_total' => $finalTotalAddons,
                'order_date' => now(),
                'created_by' => 1,
                'branch_id' => 1,
            ]);

            event(new OrderCreatedEvent($order, $orderData, $calculatedOrder, $hasDiscounts));
//            $this->handleOrder($order, $orderData, $calculatedOrder, $hasDiscounts);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function calculateOrder(array $orders): array
    {
        $finalTotal = 0;
        $finalTotalDiscount = 0;
        $finalTotalTax = 0;
        $finalTotalAddons = 0;
        $details = collect([]);

        foreach ($orders as $order) {
            $menuItem = MenuItem::with(['tax', 'discount', 'variants'])->find($order['menu_item_id']);
            $variant = $menuItem->variants->find($order['variant_id']);
            $itemPrice = $variant ? $menuItem->price + $variant->price : $menuItem->price; //todo, check variant price is additional price with menu item price or not
            // Calculate Addons Price

            $addonsTotal = 0;
            $addons = collect([]);
            foreach ($order['addons'] as $addon) {
                $addonData = Addon::find($addon['addon_id']);
                $hasVariant = isset($addon['variant_id']);
                $addonVariant = $hasVariant ? $addonData->variants->find($addon['variant_id']) : null;
                $addonPrice = $addonVariant ? $addonData->price + $addonVariant->price : $addonData->price; //todo, check variant price is additional price with menu item price or not
                $addonsTotal += $addonPrice;
                $addons->push([
                    'total_amount' => $addonPrice,
                    'addon_id' => $addon['addon_id'],
                    'variant_id' => $hasVariant ? $addon['variant_id'] : null,
                ]);
            }

            // Calculate Total Item Price
            $orderQty = $order['quantity'];
            $itemPrice = ($itemPrice + $addonsTotal) * $orderQty;

            // Apply Menu Item Discount
            $menuItemDiscountAmount = 0;
            if ($menuItem?->discount instanceof Discount) {
                $discount = $menuItem->discount;
                if ($discount->type === 'percentage') {
                    $menuItemDiscountAmount += $itemPrice * ($discount->amount / 100);
                } else {
                    $menuItemDiscountAmount += $discount->amount;
                }
            }
            $menuItemDiscountAmount = $menuItemDiscountAmount * $orderQty;
            $priceAfterDiscount = $itemPrice - $menuItemDiscountAmount;
            // Apply Tax
            $taxAmount = 0;
            if ($menuItem?->tax instanceof Tax) {
                $tax = $menuItem->tax;
                if ($menuItem->tax_included){
                    $taxApplicableAmount = 0;
                }else {
                    $taxApplicableAmount = $priceAfterDiscount;

                    if ($tax->apply_before_discount) {
                        $taxApplicableAmount = $itemPrice;
                    }

                    if ($tax->type === 'percentage') {
                        $taxAmount += $taxApplicableAmount * ($tax->rate / 100) * $orderQty;
                    } else {
                        $taxAmount += $tax->rate * $orderQty;
                    }
                }
            }

            $finalPrice = $priceAfterDiscount + $taxAmount;

            // Collect Details
            $details->push([
                'menu_item_id' => $order['menu_item_id'],
                'variant_id' => $order['variant_id'],
                'addons' => $addons,
                'quantity' => $orderQty,
                'addons_total' => $addonsTotal,
                'item_price' => $itemPrice,
                'menu_item_discount' => $menuItemDiscountAmount,
                'tax' => $taxAmount,
                'total' => $finalPrice
            ]);

            $finalTotalDiscount += $menuItemDiscountAmount;
            $finalTotalAddons += $addonsTotal;
            $finalTotalTax += $taxAmount;
            $finalTotal += $finalPrice;
        }

        return [
            'details' => $details,
            'finalTotal' => $finalTotal,
            'finalTotalTax' => $finalTotalTax,
            'finalTotalDiscount' => $finalTotalDiscount,
            'finalTotalAddons' => $finalTotalAddons,
        ];
    }

    public function handleOrder($order, $orderData, $calculatedOrder, $hasDiscounts): void
    {
        try {
            DB::beginTransaction();

            if ($hasDiscounts) {
                $order->discounts()->createMany($orderData['discounts']);
//                dump('discounts created');
            }
            // Step 3: Save each order item
            foreach ($calculatedOrder['details'] as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->menu_item_id = $item['menu_item_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->addons_total = $item['addons_total'];
                $orderItem->item_price = $item['item_price'];
                $orderItem->menu_item_discount = $item['menu_item_discount'];
//                $orderItem->additional_discount = 0;
                $orderItem->tax = $item['tax'];
                $orderItem->variant_id = $item['variant_id'];
                $orderItem->total_amount = $item['total'];
                $orderItem->save();

//                dump('orderItem created', $orderItem->id);

                $this->processOrderItemRecipeStocks($orderItem);
                // Step 4: Save addons for this item
                $addons = $item['addons'] ?? [];

                foreach ($addons as $addon) {
                    $orderItemAddon = new OrderItemAddon();
                    $orderItemAddon->order_item_id = $orderItem->id;
                    $orderItemAddon->addon_id = $addon['addon_id'];
                    $orderItemAddon->variant_id = $addon['variant_id'] ?? null;
                    $orderItemAddon->quantity = $addon['quantity'] ?? 1;
                    $orderItemAddon->total_amount = $addon['total_amount'];
                    $orderItemAddon->save();

                    dump('OrderItemAddon created', $orderItemAddon->id);

                    $this->processAddonRecipeStocks($orderItemAddon);
                }
            }

            $order->status = OrderStatus::received->value;
            $order->save();
            DB::commit();
        } catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws PosException
     */
    function processAddonRecipeStocks($orderItemAddon): void
    {
        $addonId = $orderItemAddon->addon_id;
        $variantId = $orderItemAddon->variant_id;
        $orderQty = $orderItemAddon->quantity;
        $recipe = null;
        if ($variantId != null){
            $variant = AddonVariant::find($variantId);
            $variant->load('recipe.ingredients');
            $recipe = $variant->recipe;
        } else {
            $addon = Addon::find($addonId);
            $addon->load('recipe.ingredients');
            $recipe = $addon->recipe;
        }
        if (!$recipe) {
            $error = "Recipe not found.";
            throw new PosException($error,404, [
                'error' => $error,
            ]);
        }
        $ingredients = $recipe->ingredients;

//        dump('$ingredients for $addonId item');

        $this->adjustStocks($ingredients, $orderQty);
    }
    /**
     * @throws PosException
     */
    function processOrderItemRecipeStocks($orderItem): void
    {
        $menuItemId = $orderItem->menu_item_id;
        $variantId = $orderItem->variant_id;
        $orderQty = $orderItem->quantity;
        $recipe = null;
        if ($variantId != null){
            $variant = Variant::find($variantId);
            $variant->load('recipe.ingredients');
            $recipe = $variant->recipe;
        } else {
            $menuItem = MenuItem::find($menuItemId);
            $menuItem->load('recipe.ingredients');
            $recipe = $menuItem->recipe;
        }
        if (!$recipe) {
            $error = "Recipe not found.";
            throw new PosException($error,404, [
                'error' => $error,
            ]);
        }

        $ingredients = $recipe->ingredients;
//        dump('$ingredients for order item');

        $this->adjustStocks($ingredients, $orderQty);
    }

    /**
     * @throws PosException
     */
    function adjustStocks($ingredients, $orderQty): void
    {
        foreach ($ingredients as $ingredient) {
            $stockQuery = Stock::where('product_id', $ingredient->product_id)->where('quantity', '>', 0)->orderBy('id', 'asc');;
            $availableStock = $stockQuery->sum('quantity');
            $stocks = $stockQuery->get();
            $deductibleQuantity = $ingredient->quantity * $orderQty;
//            dump('adjustStocks');
            if ($availableStock >= $deductibleQuantity) {
                foreach ($stocks as $stock) {
                    if ($deductibleQuantity <= $stock->quantity) {
                        $stock->quantity -= $deductibleQuantity;
                        $stock->save();
                        break;
                    } else {
//                        dd(111);
                        $deductibleQuantity -= $stock->quantity;
                        $stock->quantity = 0;
                        $stock->save();
                    }
                }
            }else {
                $error = "Insufficient stock for product ID: {$ingredient->product_id}";
                throw new PosException($error,400, [
                    'error' => $error
                ]);
            }
        }
    }

    private function calculateAdditionalDiscount($type, $amount, $discountAmount)
    {
        if ($type === 'percentage') {
            return $amount * ($discountAmount / 100);
        }
        return $discountAmount;
    }
    private function getAllDiscountSum($discounts = [], $menuDiscounts = 0)
    {
        $sum = 0;
        foreach ($discounts as $discount) {

            $sum += $discount['discount'];
        }
        return $sum + $menuDiscounts;
    }
}
