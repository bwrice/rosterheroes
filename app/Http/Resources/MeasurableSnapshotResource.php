<?php

namespace App\Http\Resources;

use App\Domain\Models\MeasurableSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MeasurableSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin MeasurableSnapshot
 */
class MeasurableSnapshotResource extends JsonResource
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
            'measurableID' => $this->measurable_id,
            'preBuffedAmount' => $this->pre_buffed_amount,
            'buffedAmount' => $this->buffed_amount,
            'finalAmount' => $this->final_amount
        ];
    }
}
