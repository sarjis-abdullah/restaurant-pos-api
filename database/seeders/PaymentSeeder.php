<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentDiscount;
use App\Models\PaymentLog;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = Order::find(3);
        $admin = User::find(2);

        $orderItems = OrderItem::where('order_id', $order->id)
            ->with(['menu_item', 'menu_item.discount', 'menu_item.tax'])
            ->get();

        $expectedTotalAmount = 0;
        $totalDiscountAmount = 0;
        $menuItemDiscountAmount = 0;
        $totalTaxAmount = 0;

        foreach ($orderItems as $orderItem) {
            $taxAmount = 0;
            $menuItem = $orderItem->menu_item;
            $itemPrice = $menuItem->price * $orderItem->quantity;

            if ($menuItem?->discount instanceof Discount) {
                $discount = $menuItem->discount;
                if ($discount->type === 'percentage') {
                    $menuItemDiscountAmount += $itemPrice * ($discount->amount / 100);
                } else {
                    $menuItemDiscountAmount += $discount->amount;
                }
            }

            if ($menuItem->allow_other_discount){
                //todo
                $totalDiscountAmount += 0;
            }

            $priceAfterDiscount = $itemPrice - $menuItemDiscountAmount;

            if ($menuItem?->tax instanceof Tax) {
                $tax = $menuItem->tax;
                if (!$menuItem->tax_included) {
                    if ($tax->type === 'percentage') {
                        $taxApplicableAmount = $priceAfterDiscount;
                        if ($tax?->apply_before_discount){
                            $taxApplicableAmount = $itemPrice;
                        }
                        $taxAmount += $taxApplicableAmount * ($tax->rate / 100);
                    } else {
                        $taxAmount += $tax->rate;
                    }
                }
            }

            $expectedTotalAmount += $priceAfterDiscount + $taxAmount;
            $totalTaxAmount += $taxAmount;
        }
        $totalDiscountAmount += $menuItemDiscountAmount;
        $customerPaysAmount = min($expectedTotalAmount, 10);

        $dueAmount = 0;
        $paymentType = 'full-payment';
        if ($expectedTotalAmount > $customerPaysAmount){
            $dueAmount = $expectedTotalAmount - $customerPaysAmount;
            $paymentType = 'partial-payment';
        }

        $payment = Payment::create([
            'total_amount' => $expectedTotalAmount,
            'paid_amount' => $customerPaysAmount,
            'due_amount' => $dueAmount,
            'tax_amount' => $totalTaxAmount,
            'discount_amount' => $totalDiscountAmount,
            'method' => 'cash',
            'type' => $paymentType,
            'status' => 'success',
            'transaction_id' => uniqid(),
            'order_id' => $order->id,
            'created_by' => $admin->id,
            'received_by' => $admin->id,
            'branch_id' => $order->branch_id,
        ]);

        PaymentLog::create([
            'payment_id' => $payment->id,
            'amount' => $payment->paid_amount,
            'method' => 'cash',
            'status' => 'success',
            'date' => now(),
        ]);

        if ($menuItemDiscountAmount > 0){
            PaymentDiscount::create([
                'payment_id' => $payment->id,
                'amount' => $menuItemDiscountAmount,
                'type' => 'MenuItem',
            ]);
        }

        dump('Payment created successfully:', $payment->toArray());
    }
}
