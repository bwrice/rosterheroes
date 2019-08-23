<?php

namespace App\Http\Resources;

use App\Domain\Models\ItemClass;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemClassResource
 * @package App\Http\Resources
 *
 * @mixin ItemClass
 */
class ItemClassResource extends JsonResource
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
