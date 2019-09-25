<?php

namespace App\Http\Resources;

use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\HasSlots;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HasSlotsResource
 * @package App\Http\Resources
 */
class HasSlotsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var HasSlots $this */
        return [
            'uniqueIdentifier' => $this->getUniqueIdentifier(),
            'class' => class_basename($this->resource)
        ];
    }
}
