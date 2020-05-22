<?php

namespace App\Http\Resources;

use App\Domain\DataTransferObjects\StatMeasurableBonus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HeroStatBonusResource
 * @package App\Http\Resources
 *
 * @mixin StatMeasurableBonus
 */
class StatMeasurableBonusResource extends JsonResource
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
            'statTypeID' => $this->getStatType()->id,
            'measurableTypeID' => $this->getMeasurable()->measurable_type_id,
            'percentModifier' => $this->getPercentModifier()
        ];
    }
}
