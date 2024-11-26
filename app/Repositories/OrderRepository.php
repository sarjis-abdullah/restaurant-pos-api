<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Models\Addon;
use App\Models\Discount;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Tax;
use App\Repositories\Contracts\OrderInterface;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function saveOrder(array $orderData, $membershipDiscount = null, $promoCode = null)
    {
        DB::beginTransaction();

        try {
            // Step 1: Calculate the order
            $calculatedOrder = $this->calculateOrder($orderData['orders'], $membershipDiscount, $promoCode);

//            dd($calculatedOrder['details']);

            $finalTotal = $calculatedOrder['finalTotal'];
            $finalTotalTax = $calculatedOrder['finalTotalTax'];
            $finalTotalDiscount = $calculatedOrder['finalTotalDiscount'];
            $finalTotalAddons = $calculatedOrder['finalTotalAddons'];
            $order = Order::create([
                'total_price' => $finalTotal,
                'total_tax' => $finalTotalTax,
                'total_discount' => $finalTotalDiscount,
                'total_addons_price' => $finalTotalAddons,
                'order_date' => now(),
                'created_by' => 1,
                'branch_id' => 1,
            ]);

            // Step 3: Save each order item
            foreach ($calculatedOrder['details'] as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->menu_item_id = $item['menu_item_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->addons_total = $item['addons_total'];
                $orderItem->item_price = $item['item_price'];
                $orderItem->menu_item_discount = $item['menu_item_discount'];
                $orderItem->additional_discount = $item['additional_discount'];
                $orderItem->tax_amount = $item['tax'];
                $orderItem->variant_id = $item['variant_id'];
                $orderItem->total_price = $item['total'];
                $orderItem->save();

                // Step 4: Save addons for this item
                $addons = $item['addons'] ?? [];

                foreach ($addons as $addon) {
                    $orderItemAddon = new OrderItemAddon();
                    $orderItemAddon->order_item_id = $orderItem->id;
                    $orderItemAddon->addon_id = $addon['addon_id'];
                    $orderItemAddon->variant_id = $addon['variant_id'] ?? null;
                    $orderItemAddon->quantity = $addon['quantity'] ?? 1;
                    $orderItemAddon->total_price = $addon['total_price'];
                    $orderItemAddon->save();
                }
            }
//continue;
            // Step 5: Save order-level discounts (membership, promo code)
//            if ($membershipDiscount || $promoCode) {
//                $discounts = [];
//
//                if ($membershipDiscount) {
//                    $discounts[] = [
//                        'order_id' => $order->id,
//                        'type' => 'membership',
//                        'amount' => $membershipDiscount['amount'],
//                        'created_at' => now(),
//                        'updated_at' => now()
//                    ];
//                }
//
//                if ($promoCode) {
//                    $promoDiscount = Discount::where('promo_code', $promoCode)
//                        ->where('is_active', true)
//                        ->where('start_date', '<=', now())
//                        ->where('end_date', '>=', now())
//                        ->first();
//
//                    if ($promoDiscount) {
//                        $discounts[] = [
//                            'order_id' => $order->id,
//                            'type' => 'promo_code',
//                            'amount' => $promoDiscount->amount,
//                            'created_at' => now(),
//                            'updated_at' => now()
//                        ];
//                    }
//                }
//
//                if (!empty($discounts)) {
//                    OrderDiscount::insert($discounts);
//                }
//            }

            DB::commit();

            $order->load('order_items');
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public function calculateOrder(array $orders, $membershipDiscount = null, $promoCode = null)
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
                    'total_price' => $addonPrice,
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
                $taxApplicableAmount = $menuItem->tax_included ? 0 : $priceAfterDiscount;

                if ($tax->apply_before_discount) {
                    $taxApplicableAmount = $itemPrice;
                }

                if ($tax->type === 'percentage') {
                    $taxAmount += $taxApplicableAmount * ($tax->rate / 100) * $orderQty;
                } else {
                    $taxAmount += $tax->rate * $orderQty;
                }
            }

            // Handle Additional Discounts (Membership and Promo Code)
            $additionalDiscountAmount = 0;
            if ($menuItem->allow_other_discount) {
                if ($membershipDiscount) {
                    $additionalDiscountAmount += $this->calculateAdditionalDiscount(
                        $membershipDiscount, $priceAfterDiscount
                    );
                }

                if ($promoCode) {
                    $promoDiscount = Discount::where('promo_code', $promoCode)
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    if ($promoDiscount) {
                        $additionalDiscountAmount += $this->calculateAdditionalDiscount(
                            $promoDiscount, $priceAfterDiscount
                        );
                    }
                }
            }

            $totalAfterDiscounts = $priceAfterDiscount - $additionalDiscountAmount;

            $finalPrice = $totalAfterDiscounts + $taxAmount;

            // Collect Details
            $details->push([
                'menu_item_id' => $order['menu_item_id'],
                'variant_id' => $order['variant_id'],
                'addons' => $addons,
                'quantity' => $orderQty,
                'addons_total' => $addonsTotal,
                'item_price' => $itemPrice,
                'menu_item_discount' => $menuItemDiscountAmount,
                'additional_discount' => $additionalDiscountAmount,
                'tax' => $taxAmount,
                'total' => $finalPrice
            ]);

//            OrderItem::create([
//                'order_id' => $order['id'],
//                'menu_item_id' => $order['menu_item_id'],
//                'variant_id' => $order['variant_id'],
//                'quantity' => $orderQty,
//                'item_price' => $itemPrice,
//                'menu_item_discount' => $menuItemDiscountAmount,
//                'additional_discount' => $additionalDiscountAmount,
//                'tax_amount' => $taxAmount,
//                'total_price' => $finalPrice,
//            ]);
            $finalTotalDiscount += $menuItemDiscountAmount +  $additionalDiscountAmount;
            $finalTotalAddons += $addonsTotal;
            $finalTotalTax += $taxAmount;
            $finalTotal += $finalPrice;
        }

//        Order::create([
//            'total_price' => $finalPrice,
//            'total_discount' => $finalTotalDiscount,
//            'total_tax' => $finalTotalTax,
//            'order_date' => now(),
//            'created_by' => Auth::user()->id,
//            'branch_id' => 1,
//        ]);

        return [
            'details' => $details,
            'finalTotal' => $finalTotal,
            'finalTotalTax' => $finalTotalTax,
            'finalTotalDiscount' => $finalTotalDiscount,
            'finalTotalAddons' => $finalTotalAddons,
        ];
    }

    private function calculateAdditionalDiscount($discount, $amount)
    {
        if ($discount->type === 'percentage') {
            return $amount * ($discount->amount / 100);
        }
        return $discount->amount;
    }
}
