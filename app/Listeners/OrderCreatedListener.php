<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Events\OrderCreatedEvent;
use App\Exceptions\PosException;
use App\Models\Addon;
use App\Models\AddonVariant;
use App\Models\MenuItem;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Stock;
use App\Models\Variant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class OrderCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @throws PosException
     */
    public function handle(OrderCreatedEvent $event): void
    {
        $order = $event->order;
        $orderData = $event->orderData;
        $calculatedOrder = $event->calculatedOrder;
        $hasDiscounts = $event->hasDiscounts;

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

//                    dump('OrderItemAddon created', $orderItemAddon->id);

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
}
