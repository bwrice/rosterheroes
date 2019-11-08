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
            'travelCost' => $this->travel_cost,
            'viewBox' => new ViewBoxResource($this->getViewBox()),
            'vectorPaths' => $this->vector_paths,
            'continentID' => $this->continent_id,
            'territoryID' => $this->territory_id,
            'borderUuids' => $this->borders->toUuids()
        ];
    }
}
