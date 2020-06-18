<?php

namespace App\Http\Resources;

use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Shop;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ShopResource
 * @package App\Http\Resources
 *
 * @mixin Shop
 */
class ShopResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'tier' => $this->tier,
            'items' => ItemResource::collection($this->availableItems)
        ];
    }
}
