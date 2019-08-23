<?php

namespace App\Http\Resources;

use App\Domain\Models\ItemType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemTypeResource
 * @package App\Http\Resources
 *
 * @mixin ItemType
 */
class ItemTypeResource extends JsonResource
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
            'grade' => $this->grade,
            'itemBase' => new ItemBaseResource($this->itemBase)
        ];
    }
}
