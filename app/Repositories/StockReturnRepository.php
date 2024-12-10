<?php

namespace App\Repositories;

use App\Exceptions\PosException;
use App\Models\Addon;
use App\Models\Stock;
use App\Models\StockLog;
use App\Repositories\Contracts\StockReturnInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class StockReturnRepository extends BaseRepository implements StockReturnInterface
{
    /**
     * @throws Exception
     */
    public function save(array $data): \ArrayAccess
    {
        try {
            DB::beginTransaction();

            $stock = Stock::find($data['stock_id']);
            if ($stock instanceof Stock && $stock->quantity >= $data['quantity']) {
                $prevQuantity = $stock->quantity;
                $stock->quantity -= $data['quantity'];
                $stock->save();

                StockLog::create([
                    'stock_id' => $stock->id,
                    'product_id' => $stock->product_id,
                    'prev_quantity' => $prevQuantity,
                    'new_quantity' => $stock->quantity,
                    'type' => 'return',
                ]);
            }else {
                throw new PosException('Stock is not available!.', 422, [
                    'error' => ['Stock is not available!'],
                ]);
            }
            $item = parent::save($data);

            DB::commit();

            return $item;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
