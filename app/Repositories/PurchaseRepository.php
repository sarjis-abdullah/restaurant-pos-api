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
                $purchasePrice = $purchaseProduct['purchase_price'];
                $sellingPrice = $purchaseProduct['selling_price'];
                $tax = $purchaseProduct['tax'];
                $discount = $purchaseProduct['discount'];
                $taxAmount = $taxType == 'percentage' ? $quantity * $purchasePrice * ($tax/100) : $tax;
                $discountAmount = $discountType == 'percentage' ? $quantity * $purchasePrice * ($discount/100) : $discount;
                $suppingCost = $purchaseProduct['allocated_shipping_cost'];
                $subtotal = ($quantity * $purchasePrice) + $taxAmount - $discountAmount + $suppingCost;
//                dd($subtotal,$quantity, $purchasePrice,$tax, $taxAmount,$discountAmount, $suppingCost);
                $finalTotal += $subtotal;
                $finalDiscount += $discountAmount;
                $finalTax += $taxAmount;
                $purchaseAbleItems->push([
                    ...$purchaseProduct,
                    'product_id'     => $purchaseProduct['product_id'],
                    'quantity'       => $quantity,
                    'purchase_price' => $purchasePrice,
                    'selling_price'  => $sellingPrice,
                    'tax'     => $purchaseProduct['tax'],
                    'discount'=> $purchaseProduct['discount'],
                    'discount_type'  => $purchaseProduct['discount_type'],
                    'tax_type'       => $purchaseProduct['tax_type'],
                    'cost_per_unit'  => $purchaseProduct['cost_per_unit'],
                    'expire_date'  => $purchaseProduct['expire_date'],
                    'allocated_shipping_cost'  => $purchaseProduct['allocated_shipping_cost'],
                    'subtotal'  => 0, //todo, remove it

                ]);
            }

            $purchase = Purchase::create([
                'supplier_id'    => $data['supplier_id'],
                'purchase_date'  => $data['purchase_date'] ?? now(), // Random date within the last 30 days
                'total_amount'   => $finalTotal,     // Random amount between 50.00 and 500.00
                'discount' => $finalDiscount,         // Random discount
                'tax'      => $finalTax,       // Random tax
                'shipping_cost'   => $data['shipping_cost'],        // Random shipping cost
                'status'          => $data['status'],
                'branch_id'      => $data['branch_id'],
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
                $item['subtotal'] += $proportionalShipping;
                return $item;
            }, $purchaseAbleItems->toArray());

            foreach ($purchaseAbleItems as $item) {
                $purchaseProductId = PurchaseProduct::create($item)->id;

                if (isset($item['stockId'])){
                    $stock = Stock::where('id', $item['stockId'])->where('product_id', $item['product_id'])->first();
                    if ($stock instanceof Stock){
                        $stock->quantity = $stock->quantity + $item['quantity'];
                        $stock->save();
                    }
                }else{
                    $stock = Stock::create([
                        'sku' => uniqid('prod_'),
                        'product_id' => $item['product_id'],
                        'purchase_product_id' => $purchaseProductId,
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
