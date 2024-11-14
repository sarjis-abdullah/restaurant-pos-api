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
        // Get an order to process payment
        $order = Order::find(3); // Assume order ID is 1
        $admin = User::find(2);  // The user handling the payment

        // Retrieve order items for the order
        $orderItems = OrderItem::where('order_id', $order->id)
            ->with(['menu_item', 'menu_item.discount', 'menu_item.tax'])
            ->get();

//        dump('Payment created successfully:', $orderItems->toArray());
        $expectedTotalAmount = 0;
        $totalDiscountAmount = 0;
        $menuItemDiscountAmount = 0;
        $taxAmount = 0;

        foreach ($orderItems as $orderItem) {
            $menuItem = $orderItem->menu_item;
            $itemPrice = $menuItem->price * $orderItem->quantity;

//            dd($orderItem, $menuItem);
            // Apply Discount if available
            if ($menuItem?->discount instanceof Discount) {
                $discount = $menuItem->discount;
                if ($discount->type === 'percentage') {
                    $menuItemDiscountAmount += $itemPrice * ($discount->amount / 100);
                } else {
                    $menuItemDiscountAmount += $discount->amount;
                }
            }

            // Adjust price after discount
            $priceAfterDiscount = $itemPrice - $menuItemDiscountAmount;

            // Apply Tax if applicable
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
        }
        dd($expectedTotalAmount);
        $totalDiscountAmount += $menuItemDiscountAmount;
        $customerPaysAmount = min($expectedTotalAmount, 10);

        $dueAmount = 0;
        $paymentType = 'full-payment';
        if ($expectedTotalAmount > $customerPaysAmount){
            $dueAmount = $expectedTotalAmount - $customerPaysAmount;
            $paymentType = 'partial-payment';
        }
        // Create Payment record
        $payment = Payment::create([
            'total_amount' => $expectedTotalAmount,
            'paid_amount' => $customerPaysAmount, // assuming full payment is done
            'due_amount' => $dueAmount, // no due for full payment
            'tax_amount' => $taxAmount,
            'discount_amount' => $totalDiscountAmount,
            'method' => 'cash', // or 'card', 'bkash', etc.
            'type' => $paymentType,
            'status' => 'success',
            'transaction_id' => uniqid(),
//            'reference_number' => 'REF' . strtoupper(uniqid()),
//            'transaction_number' => 'TXN' . strtoupper(uniqid()),
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

        PaymentDiscount::create([
            'payment_id' => $payment->id,
            'amount' => $menuItemDiscountAmount,
            'type' => 'MenuItem',
        ]);

        dump('Payment created successfully:', $payment->toArray());
    }
}
