<?php

namespace App\Http\Resources;

use App\Domain\Models\SquadSnapshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SquadSnapshotResource
 * @package App\Http\Resources
 *
 * @mixin SquadSnapshot
 */
class SquadSnapshotResource extends JsonResource
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
            'squadRankID' => $this->squad_rank_id,
            'week' => new WeekResource($this->week),
            'experience' => $this->experience,
            'heroSnapshots' => HeroSnapshotResource::collection($this->heroSnapshots)
        ];
    }
}
