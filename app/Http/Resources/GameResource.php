<?php

namespace App\Http\Resources;

use App\Domain\Models\Game;
use App\Domain\Models\Team;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GameResource
 * @package App\Http\Resources
 *
 * @mixin Game
 */
class GameResource extends JsonResource
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
            'startsAt' => $this->starts_at,
            'description' => $this->getSimpleDescription(),
            'homeTeam' => new TeamResource($this->homeTeam),
            'awayTeam' => new TeamResource($this->awayTeam)
        ];
    }
}
