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
            'squads_count' => $this->squads_count,
            'quests_count' => $this->quests_count
        ];

        return array_merge($parent, $data);
    }
}
