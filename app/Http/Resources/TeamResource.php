<?php

namespace App\Http\Resources;

use App\Domain\Models\Team;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TeamResource
 * @package App\Http\Resources
 *
 * @mixin Team
 */
class TeamResource extends JsonResource
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
            'name' => $this->name,
            'location' => $this->location,
            'abbreviation' => $this->abbreviation
        ];
    }
}
