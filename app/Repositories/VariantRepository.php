<?php

namespace App\Repositories;

use App\Models\Variant;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Contracts\VariantInterface;

class VariantRepository extends BaseRepository implements VariantInterface
{
    public function saveMultipleVariants(array $data)
    {
        $menuItemId = $data['menu_item_id'];
        $currentTimestamp = now();
        $updatedVariants = array_map(function ($variant) use ($menuItemId, $currentTimestamp) {
            $variant['menu_item_id'] = $menuItemId;
            $variant['created_at'] = $currentTimestamp;
            $variant['updated_at'] = $currentTimestamp;
            return $variant;
        }, $data['variants']);

        Variant::insert($updatedVariants);
        return Variant::where(['menu_item_id' => $menuItemId])->where('created_at', $currentTimestamp)->get();
    }
}
