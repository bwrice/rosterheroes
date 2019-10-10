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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->color,
            'travel_cost' => $this->travel_cost,
            'view_box' => $this->getViewBox(),
            'vector_paths' => $this->vector_paths,
            'continent_id' => $this->continent_id,
            'territory_id' => $this->territory_id,
            'borderUuids' => $this->borders->toUuids(),
            'continent' => new ContinentResource($this->whenLoaded('continent')),
            'territory' => new TerritoryResource($this->whenLoaded('territory'))
        ];
    }
}
