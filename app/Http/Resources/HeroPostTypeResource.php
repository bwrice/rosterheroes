<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HeroPostTypeResource
 * @package App\Http\Resources
 *
 * @mixin HeroPostType
 */
class HeroPostTypeResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'recruitmentCost' => $this->recruitmentCost,
            'heroRaceIDs' => $this->heroRaces->map(function (HeroRace $heroRace) {
                return $heroRace->id;
            })
        ];
    }
}
