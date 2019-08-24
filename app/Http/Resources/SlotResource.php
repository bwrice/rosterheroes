<?php

namespace App\Http\Resources;

use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SlotResource
 * @package App\Http\Resources
 *
 * @mixin Slot
 */
class SlotResource extends JsonResource
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
            'item' => $this->whenLoaded('item', new ItemResource($this->item)),
            'slotType' => new SlotTypeResource($this->slotType)
        ];
    }
}
