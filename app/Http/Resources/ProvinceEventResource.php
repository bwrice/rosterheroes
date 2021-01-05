<?php

namespace App\Http\Resources;

use App\Domain\Models\ProvinceEvent;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProvinceEventResource
 * @package App\Http\Resources
 *
 * @mixin ProvinceEvent
 */
class ProvinceEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'uuid' => $this->uuid,
            'provinceUuid' => $this->province->uuid,
            'eventType' => $this->event_type,
            'squad' => $this->squad ? [
                'uuid' => $this->squad->uuid,
                'name' => $this->squad->name
            ] : null,
            'happenedAt' => $this->happened_at,
            'extra' => $this->extra
        ], $this->getSupplementalResourceData());
    }
}
