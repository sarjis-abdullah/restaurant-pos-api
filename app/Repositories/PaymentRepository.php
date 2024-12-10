<?php

namespace App\Repositories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Exceptions\PosException;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\Contracts\PaymentInterface;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends BaseRepository implements PaymentInterface
{
    /**
     * @throws PosException
     */
    public function save(array $data): \ArrayAccess
    {
        $payments = $data['payments'];
        $items = collect([]);
        DB::beginTransaction();
        try {
            foreach ($payments as $payment) {
                $order = Order::find($payment['order_id']);
                $roundedAmount = ceil($order->total_amount);
                $requestRoundedAmount = ceil($payment['amount']);
                $roundOffAmount = $roundedAmount - $order->total_amount;
                if ($roundedAmount == $requestRoundedAmount) {
                    $singleItem = [
                        'payable_id' => $payment['order_id'],
                        'payable_type' => Order::class,
                        'status' => $payment['method'] == PaymentMethod::cash->value ? PaymentStatus::success : PaymentStatus::pending,
                        'amount' => $roundedAmount,
                        'round_off_amount' => $roundOffAmount,
                        'method' => $payment['method'],
                        'reference_number' => $payment['reference_number'] ?? '',
                        'transaction_number' => $payment['transaction_number'] ?? '',
                        'transaction_id' => uniqid('tnx_'),
                        'created_by' => Auth::user()->id,
                        'branch_id' => 1,
                    ];
                    $items->push($singleItem);
                } else {
                    throw new PosException('The amount value is not the right one!.', 422, [
                        'error' => ['The amount value is not the right one!'],
                    ]);
                }
            }
            Payment::insert($items->toArray());

            DB::commit();


        } catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

        return $items;
    }
}
