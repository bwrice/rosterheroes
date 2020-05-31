<?php

namespace App\Http\Resources;

use App\Domain\Models\Support\GearSlots\GearSlot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GearSlotResource
 * @package App\Http\Resources
 *
 * @mixin GearSlot
 */
class GearSlotResource extends JsonResource
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
            'type' => $this->getType(),
            'item' => $this->getItem() ? new ItemResource($this->getItem()) : null,
            'priority' => $this->getPriority(),
        ];
    }
}
