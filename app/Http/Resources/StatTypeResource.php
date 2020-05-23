<?php

namespace App\Http\Resources;

use App\Domain\Models\StatType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StatTypeResource
 * @package App\Http\Resources
 *
 * @mixin StatType
 */
class StatTypeResource extends JsonResource
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
            'sportID' => $this->sport_id,
            'name' => $this->name,
            'simpleName' => $this->getBehavior()->getSimpleName(),
            'pointsPer' => $this->getBehavior()->getPointsPer()
        ];
    }
}
