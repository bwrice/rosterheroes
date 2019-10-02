<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroRace;
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
            'name' => $this->name,
            'icon' => $this->getBehavior()->getIcon(),
            'positions' => PositionResource::collection($this->whenLoaded('positions'))
        ];
    }
}
