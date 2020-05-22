<?php

namespace App\Http\Resources;

use App\Domain\Models\League;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LeagueResource
 * @package App\Http\Resources
 *
 * @mixin League
 */
class LeagueResource extends JsonResource
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
            'sportID' => $this->sport_id,
            'abbreviation' => $this->abbreviation
        ];
    }
}
