<?php

namespace App\Http\Resources;

use App\Domain\Models\Titan;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TitanResource
 * @package App\Http\Resources
 *
 * @mixin Titan
 */
class TitanResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'combat_position_id' => $this->combat_position_id
        ];
    }
}
