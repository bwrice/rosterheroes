<?php

namespace App\Http\Resources;

use App\Domain\Models\Measurable;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MeasurableResource
 * @package App\Http\Resources
 *
 * @mixin Measurable
 */
class MeasurableResource extends JsonResource
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
            'amountRaised' => $this->amount_raised,
            'measurableType' => new MeasurableTypeResource($this->measurableType),
            'costToRaise' => $this->getCostToRaise(),
            'preBuffedAmount' => $this->getPreBuffedAmount(),
            'buffedAmount' => $this->getBuffedAmount(),
            'spentOnRaising' => $this->spentOnRaising()
        ];
    }
}
