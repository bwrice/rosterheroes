<?php

namespace App\Http\Resources;

use App\Domain\Models\Position;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PositionResource
 * @package App\Http\Resources
 *
 * @mixin Position
 */
class PositionResource extends JsonResource
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
            'name' => $this->name,
            'abbreviation' => $this->getBehavior()->getAbbreviation()
        ];
    }
}
