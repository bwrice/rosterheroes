<?php

namespace App\Http\Resources;

use App\Domain\Models\TargetRange;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TargetRangeResource
 * @package App\Http\Resources
 *
 * @mixin TargetRange
 */
class TargetRangeResource extends JsonResource
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
            'name' => $this->name
        ];
    }
}
