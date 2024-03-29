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
            'essenceCost' => $this->essence_cost,
            'energy' => $this->energy,
            'playerGameLog' => new SpiritGameLogResource($this->playerGameLog),
        ];
    }
}
