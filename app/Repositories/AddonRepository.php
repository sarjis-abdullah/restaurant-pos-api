<?php

namespace App\Repositories;

use App\Models\Addon;
use App\Repositories\Contracts\AddonInterface;

class AddonRepository extends BaseRepository implements AddonInterface
{
    public function saveMultipleAddons(array $data)
    {
        $menuItemId = $data['menu_item_id'];
        $currentTimestamp = now();
        $updatedVariants = array_map(function ($variant) use ($menuItemId, $currentTimestamp) {
            $variant['menu_item_id'] = $menuItemId;
            $variant['created_at'] = $currentTimestamp;
            $variant['updated_at'] = $currentTimestamp;
            return $variant;
        }, $data['addons']);

        Addon::insert($updatedVariants);
        return Addon::where(['menu_item_id' => $menuItemId])->where('created_at', $currentTimestamp)->get();
    }
}
