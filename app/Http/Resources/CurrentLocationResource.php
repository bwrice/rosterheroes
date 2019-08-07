<?php

namespace App\Http\Resources;

class CurrentLocationResource extends ProvinceResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $parent = parent::toArray($request);

        $data = [
            'borders' => ProvinceResource::collection($this->whenLoaded('borders')),
            'squads_count' => $this->squads_count
        ];

        return array_merge($parent, $data);
    }
}
