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
            'amount_raised' => $this->amount_raised,
            'measurable_type' => new MeasurableTypeResource($this->whenLoaded('measurableType')),
            'cost_to_raise' => $this->getCostToRaise(),
            'current_amount' => $this->getCurrentAmount()
        ];
    }
}
