<?php

namespace App\Http\Resources;

use App\Domain\Models\ItemSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin ItemSnapshot
 */
class ItemSnapshotResource extends JsonResource
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
            'itemTypeID' => $this->item_type_id,
            'materialID' => $this->material_id,
            'name' => $this->name,
            'protection' => $this->protection,
            'weight' => $this->weight,
            'value' => $this->value,
            'blockChance' => $this->block_chance,
            'attackSnapshots' => AttackSnapshotResource::collection($this->attackSnapshots)
        ];
    }
}
