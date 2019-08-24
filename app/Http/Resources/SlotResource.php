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
        $hasItem = $this->slottable instanceof Item;

        return [
            'item' => $this->when($hasItem, function() {
                return new ItemResource($this->slottable);
            }),
            'slotType' => new SlotTypeResource($this->slotType)
        ];
    }
}
