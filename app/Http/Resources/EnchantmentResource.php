<?php

namespace App\Http\Resources;

use App\Domain\Models\Enchantment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class EnchantmentResource
 * @package App\Http\Resources
 *
 * @mixin Enchantment
 */
class EnchantmentResource extends JsonResource
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
            'measurableBoosts' => MeasurableBoostResource::collection($this->measurableBoosts)->collection->each(function (MeasurableBoostResource $boostResource) {
                $boostResource->setBoostsMeasurables($this->resource);
            }),
        ];
    }
}
