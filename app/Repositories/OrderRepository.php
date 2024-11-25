<?php

namespace App\Repositories;

use App\Models\Addon;
use App\Models\Discount;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Tax;
use App\Repositories\Contracts\OrderInterface;
use App\Repositories\Contracts\UserInterface;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function calculateOrder(array $orders, $membershipDiscount = null, $promoCode = null)
    {
        $finalTotal = 0;
        $details = [];

        foreach ($orders['orders'] as $order) {
            $menuItem = MenuItem::with(['tax', 'discount', 'variants'])->find($order['menu_item_id']);
            $variant = $menuItem->variants->find($order['variant_id']);
            $itemPrice = $variant ? $menuItem->price + $variant->price : $menuItem->price; //todo, check variant price is additional price with menu item price or not
            // Calculate Addons Price
            $addonsTotal = 0;
            foreach ($order['addons'] as $addon) {
                $addonData = Addon::find($addon['addon_id']);
                $addonVariant = isset($addon['variant_id']) ? $addonData->variants->find($addon['variant_id']) : null;
                $addonPrice = $addonVariant ? $addonVariant->price : $addonData->price; //todo, check variant price is additional price with menu item price or not
                $addonsTotal += $addonPrice;
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
            $details[] = [
                'menu_item_id' => $order['menu_item_id'],
                'quantity' => $orderQty,
                'addons_total' => $addonsTotal,
                'item_price' => $itemPrice,
                'menu_item_discount' => $menuItemDiscountAmount,
                'additional_discount' => $additionalDiscountAmount,
                'tax' => $taxAmount,
                'total' => $finalPrice
            ];

            $finalTotal += $finalPrice;
        }

        return [
            'details' => $details,
            'final_total' => $finalTotal
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
