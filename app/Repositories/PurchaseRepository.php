<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Stock;
use App\Repositories\Contracts\PurchaseInterface;
use Illuminate\Support\Facades\DB;

class PurchaseRepository extends BaseRepository implements PurchaseInterface
{
    /**
     * @throws \Exception
     */
    public function save(array $data): \ArrayAccess
    {
        try {
            DB::beginTransaction();

            $purchaseAbleItems = collect([]);
            $finalTotal = 0;
            $finalDiscount = 0;
            $finalTax = 0;
            $finalCost = 0;
            $shippingCost = rand(20, 21) * 10;
            foreach ($data['purchaseProducts'] as $purchaseProduct) {
                $discountType = $purchaseProduct['tax_type'];
                $taxType = $purchaseProduct['tax_type'];
                $quantity = $purchaseProduct['quantity'];
                $purchasePrice =$purchaseProduct['purchase_price'];
                $sellingPrice = $purchaseProduct['purchase_price'];
                $taxAmount = $taxType == 'percentage' ? $purchasePrice * (5/100) : 5;
                $discountAmount = $discountType == 'percentage' ? $purchasePrice * (2/100) : 2;

                $subtotal = ($quantity * $purchasePrice) + $taxAmount - $discountAmount;
                $finalTotal += $subtotal;
                $finalDiscount += $discountAmount;
                $finalTax += $taxAmount;
                $purchaseAbleItems->push([
                    ...$purchaseProduct,
                    'product_id'     => $purchaseProduct['product_id'],
                    'quantity'       => $quantity,
                    'purchase_price' => $purchasePrice,
                    'selling_price'  => $sellingPrice,
                    'tax_amount'     => $taxAmount,
                    'discount_amount'=> $discountAmount,
                    'discount_type'  => $discountType,
                    'tax_type'       => $taxType,
                    'subtotal'       => $subtotal,
                ]);
            }

            $purchase = Purchase::create([
                'supplier_id'    => $data['supplier_id'],
                'purchase_date'  => $data['purchase_date'] ?? now(), // Random date within the last 30 days
                'total_amount'   => $finalTotal,     // Random amount between 50.00 and 500.00
                'discount_amount' => $finalDiscount,         // Random discount
                'tax_amount'      => $finalTax,       // Random tax
                'shipping_cost'   => $finalCost,        // Random shipping cost
                'status'          => 'received',
            ]);


            $roundedAmount = ceil($purchase->total_amount);
            $requestRoundedAmount = ceil($data['payment']['amount']);
            $roundOffAmount = $roundedAmount - $purchase->total_amount;
            $status = 'pending';
            if ($roundedAmount == $requestRoundedAmount) {
                $status = 'completed';
            }
            $method = $data['payment']['method'];

            Payment::create([
                'payable_type' => Purchase::class,
                'payable_id' => $purchase->id,
                'amount' => $roundedAmount,
                'reference_number' => $payment['reference_number'] ?? '',
                'transaction_number' => $payment['transaction_number'] ?? '',
                'transaction_id' => uniqid('tnx_'),
                'status' => $status,
                'method' => $method,
                'round_off_amount' => $roundOffAmount,
                'branch_id' => 1,
            ]);

            $purchaseAbleItems = array_map(function ($item) use ($purchase, $shippingCost, $finalTotal) {
                $item['purchase_id'] = $purchase->id;
                $proportionalShipping = ($item['subtotal'] / $finalTotal) * $shippingCost;
                $item['allocated_shipping_cost'] = $proportionalShipping;
                $item['subtotal'] += $proportionalShipping;
                return $item;
            }, $purchaseAbleItems->toArray());

            foreach ($purchaseAbleItems as $item) {
                $costPerUnit = ($item['subtotal'] / $item['quantity']);

                PurchaseProduct::create($item);
                if (isset($item['stockId'])){
                    $stock = Stock::where('id', $item['stockId'])->where('product_id', $item['product_id'])->first();
                    if ($stock instanceof Stock){
                        $stock->quantity = $stock->quantity + $item['quantity'];
                        $stock->save();
                    }
                }else{
                    Stock::create([
                        'sku' => uniqid('prod_'),
                        'product_id' => $item['product_id'],
                        'cost_per_unit' => $costPerUnit,
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
            DB::commit();
            return $purchase;
        } catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
