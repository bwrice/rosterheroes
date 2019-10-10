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
            'id' => $this->id,
            'startsAt' => $this->starts_at,
            'description' => $this->getSimpleDescription(),
            'homeTeamID' => $this->home_team_id,
            'awayTeamID' => $this->away_team_id
        ];
    }
}
