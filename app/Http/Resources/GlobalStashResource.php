<?php

namespace App\Http\Resources;

use App\Domain\Models\Stash;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GlobalStashResource
 * @package App\Http\Resources
 *
 * @mixin Stash
 */
class GlobalStashResource extends JsonResource
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
            'itemsCount' => $this->items_count,
        ];
    }
}
