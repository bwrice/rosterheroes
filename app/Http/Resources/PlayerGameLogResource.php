<?php

namespace App\Http\Resources;

use App\Domain\Models\PlayerGameLog;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PlayerGameLogResource
 * @package App\Http\Resources
 *
 * @mixin PlayerGameLog
 */
class PlayerGameLogResource extends JsonResource
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
            'player' => new PlayerResource($this->player),
            'teamID' => $this->team_id,
            'gameID' => $this->game_id
        ];
    }
}
