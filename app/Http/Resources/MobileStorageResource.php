<?php

namespace App\Http\Resources;

use App\Domain\Models\Squad;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MobileStorageResource
 * @package App\Http\Resources
 *
 * @mixin Squad
 */
class MobileStorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'maxCapacity' => $this->mobileStorageRank->getBehavior()->getWeightCapacity(),
            'capacityUsed' => $this->getMobileStorageCapacityUsed(),
            'mobileStorageRank' => new MobileStorageRankResource($this->mobileStorageRank),
            'items' => ItemResource::collection($this->items)->collection->each(function (ItemResource $itemResource) {
                $itemResource->setHasItems($this->resource);
            })
        ];
    }
}
