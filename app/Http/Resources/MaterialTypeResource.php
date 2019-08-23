<?php

namespace App\Http\Resources;

use App\Domain\Models\MaterialType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MaterialTypeResource
 * @package App\Http\Resources
 *
 * @mixin MaterialType
 */
class MaterialTypeResource extends JsonResource
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
            'materialGroup' => new MaterialGroupResource($this->materialGroup)
        ];
    }
}
