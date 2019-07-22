<?php

namespace App\Http\Resources;

use App\Domain\Models\Province;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProvinceResource
 * @package App\Http\Resources
 *
 * @mixin Province
 */
class ProvinceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // TODO: relations?
        return [
            'name' => $this->name,
        ];
    }
}
