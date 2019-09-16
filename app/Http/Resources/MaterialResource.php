<?php

namespace App\Http\Resources;

use App\Domain\Models\Material;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MaterialTypeResource
 * @package App\Http\Resources
 *
 * @mixin Material
 */
class MaterialResource extends JsonResource
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
            'materialType' => new MaterialTypeResource($this->materialType)
        ];
    }
}
