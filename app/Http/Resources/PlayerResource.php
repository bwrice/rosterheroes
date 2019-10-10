<?php

namespace App\Http\Resources;

use App\Domain\Models\Player;
use App\Domain\Models\Position;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PlayerResource
 * @package App\Http\Resources
 *
 * @mixin Player
 */
class PlayerResource extends JsonResource
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
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'teamID' => $this->team_id,
            'positionIDs' => $this->positions->map(function (Position $position) {
                return $position->id;
            })->values()->toArray()
        ];
    }
}
