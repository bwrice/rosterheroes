<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HeroSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin HeroSnapshot
 */
class HeroSnapshotResource extends JsonResource
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
            'combatPositionID' => $this->combat_position_id,
            'measurableSnapshots' => MeasurableSnapshotResource::collection($this->measurableSnapshots),
            'itemSnapshots' => ItemSnapshotResource::collection($this->itemSnapshots)
        ];
    }
}
