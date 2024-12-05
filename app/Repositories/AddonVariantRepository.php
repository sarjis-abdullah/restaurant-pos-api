<?php

namespace App\Repositories;

use App\Models\AddonVariant;
use App\Repositories\Contracts\AddonVariantInterface;

class AddonVariantRepository extends BaseRepository implements AddonVariantInterface
{
    public function saveMultipleAddonVariants(array $data)
    {
        $addonId = $data['addon_id'];
        $currentTimestamp = now();
        $updatedVariants = array_map(function ($variant) use ($addonId, $currentTimestamp) {
            $variant['addon_id'] = $addonId;
            $variant['created_at'] = $currentTimestamp;
            $variant['updated_at'] = $currentTimestamp;
            return $variant;
        }, $data['variants']);

        AddonVariant::insert($updatedVariants);
        return AddonVariant::where(['addon_id' => $addonId])->where('created_at', $currentTimestamp)->get();
    }
}
