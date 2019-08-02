<?php

namespace App\Http\Resources;

use App\Domain\Models\Province;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProvinceResource
 * @package App\Http\Resources
 *
 * @mixin Province
 */
class ProvinceResource extends JsonResource
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
            'color' => $this->color,
            'vector_paths' => $this->vector_paths,
            'continent_id' => $this->continent_id,
            'territory_id' => $this->territory_id,
            'continent' => $this->whenLoaded('continent'),
            'territory' => $this->whenLoaded('territory')
        ];
    }
}
