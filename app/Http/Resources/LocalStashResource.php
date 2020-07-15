<?php

namespace App\Http\Resources;

use App\Domain\Models\Stash;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CurrentLocationStashResource
 * @package App\Http\Resources
 *
 * @mixin Stash
 */
class LocalStashResource extends JsonResource
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
            'uuid' => $this->uuid,
            'provinceUuid' => $this->province->uuid,
            'items' => ItemResource::collection($this->items)
        ];
    }
}
