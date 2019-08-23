<?php

namespace App\Http\Resources;

use App\Domain\Models\MaterialGroup;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MaterialGroupResource
 * @package App\Http\Resources
 *
 * @mixin MaterialGroup
 */
class MaterialGroupResource extends JsonResource
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
