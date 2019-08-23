<?php

namespace App\Http\Resources;

use App\Domain\Models\SlotType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SlotTypeResource
 * @package App\Http\Resources
 *
 * @mixin SlotType
 */
class SlotTypeResource extends JsonResource
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
            'name' => $this->name
        ];
    }
}
