<?php

namespace App\Http\Resources;

use App\Domain\Support\SlotTransaction;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SlotTransactionResource
 * @package App\Http\Resources
 *
 * @mixin SlotTransaction
 */
class SlotTransactionResource extends JsonResource
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
            'item' => new ItemResource($this->getItem()),
            'slots' => SlotResource::collection($this->getSlots()),
            'hasSlots' => new HasSlotsResource($this->getHasSlots())
        ];
    }
}
