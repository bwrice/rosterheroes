<?php

namespace App\Http\Resources;

use App\Domain\Models\DamageType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DamageTypeResource
 * @package App\Http\Resources
 *
 * @mixin DamageType
 */
class DamageTypeResource extends JsonResource
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
