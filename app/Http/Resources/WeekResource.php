<?php

namespace App\Http\Resources;

use App\Domain\Models\Week;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class WeekResource
 * @package App\Http\Resources
 *
 * @mixin Week
 */
class WeekResource extends JsonResource
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
            'uuid' => $this->uuid
        ];
    }
}
