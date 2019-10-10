<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroRace;
use App\Domain\Models\Position;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class HeroRaceResource
 * @package App\Http\Resources
 *
 * @mixin HeroRace
 */
class HeroRaceResource extends JsonResource
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
            'svg' => $this->getBehavior()->getIconSVG(),
            'positionIDs' => $this->positions->map(function(Position $position) {
                return $position->id;
            })->values()->toArray()
        ];
    }
}
