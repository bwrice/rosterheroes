<?php

namespace App\Http\Resources;

use App\Domain\Models\MeasurableType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MeasurableTypeResource
 * @package App\Http\Resources
 *
 * @mixin MeasurableType
 */
class MeasurableTypeResource extends JsonResource
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
            'group' => $this->getMeasurableGroup()
        ];
    }
}
