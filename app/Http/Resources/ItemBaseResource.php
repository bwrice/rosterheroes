<?php

namespace App\Http\Resources;

use App\Domain\Models\ItemBase;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemBaseResource
 * @package App\Http\Resources
 *
 * @mixin ItemBase
 */
class ItemBaseResource extends JsonResource
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
            'name' => $this->name,
            'slotTypes' => SlotTypeResource::collection($this->slotTypes)
        ];
    }
}
