<?php

namespace App\Http\Resources;

use App\Domain\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemResource
 * @package App\Http\Resources
 *
 * @mixin Item
 */
class ItemResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->getItemName(),
            'itemClass' => new ItemClassResource($this->itemClass),
            'itemType' => new ItemTypeResource($this->itemType),
            'materialType' => new MaterialTypeResource($this->materialType),
            'attacks' => AttackResource::collection($this->attacks)->collection->each(function (AttackResource $attackResource) {
                $attackResource->setHasAttacks($this->resource);
            })
        ];
    }
}
