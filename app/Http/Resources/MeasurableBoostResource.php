<?php

namespace App\Http\Resources;

use App\Domain\Models\MeasurableBoost;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MeasurableBoostResource
 * @package App\Http\Resources
 *
 * @mixin MeasurableBoost
 */
class MeasurableBoostResource extends JsonResource
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
            'measurableType' => new MeasurableTypeResource($this->measurableType),
            'boost_level' => $this->boost_level
        ];
    }
}
