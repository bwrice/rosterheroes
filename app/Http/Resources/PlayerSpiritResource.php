<?php

namespace App\Http\Resources;

use App\Domain\Models\PlayerSpirit;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PlayerSpiritResource
 * @package App\Http\Resources
 *
 * @mixin PlayerSpirit
 */
class PlayerSpiritResource extends JsonResource
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
            'energy' => $this->energy,
            'player' => new PlayerResource($this->whenLoaded('player')),
            'game' => new GameResource($this->whenLoaded('game'))
        ];
    }
}
