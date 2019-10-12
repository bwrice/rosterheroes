<?php

namespace App\Http\Resources;

use App\Domain\Models\Sport;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SportResource
 * @package App\Http\Resources
 *
 * @mixin Sport
 */
class SportResource extends JsonResource
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
            'color' => $this->getBehavior()->getColor()
        ];
    }
}
