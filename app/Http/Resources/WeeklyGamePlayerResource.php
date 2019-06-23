<?php

namespace App\Http\Resources;

use App\Domain\Models\WeeklyGamePlayer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class WeeklyGamePlayerResource
 * @package App\Http\Resources
 *
 * @mixin WeeklyGamePlayer
 */
class WeeklyGamePlayerResource extends JsonResource
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
            'salary' => $this->salary,
            'effectiveness' => $this->effectiveness,
            'player' => new PlayerResource($this->whenLoaded('player')),
            'game' => new GameResource($this->whenLoaded('game'))
        ];
    }
}
