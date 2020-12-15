<?php

namespace App\Http\Resources;

use App\Domain\Models\SideQuestResult;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SideQuestResultResource
 * @package App\Http\Resources
 *
 * @mixin SideQuestResult
 */
class SideQuestResultResource extends JsonResource
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
            'experienceRewarded' => $this->experience_rewarded,
            'favorRewarded' => $this->favor_rewarded,
            'sideQuestSnapshot' => new SideQuestSnapshotResource($this->sideQuestSnapshot)
        ];
    }
}
