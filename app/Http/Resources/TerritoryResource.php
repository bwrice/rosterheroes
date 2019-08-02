<?php

namespace App\Http\Resources;

use App\Domain\Models\Territory;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TerritoryResource
 * @package App\Http\Resources
 *
 * @mixin Territory
 */
class TerritoryResource extends JsonResource
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
            'name' => $this->name
        ];
    }
}
