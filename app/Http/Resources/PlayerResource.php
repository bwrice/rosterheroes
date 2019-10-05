<?php

namespace App\Http\Resources;

use App\Domain\Models\Player;
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
            'full_name' => $this->fullName(),
            'team' => new TeamResource($this->whenLoaded('team')),
            'positions' => PositionResource::collection($this->whenLoaded('positions'))
        ];
    }
}
