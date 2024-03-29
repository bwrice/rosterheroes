<?php

namespace App\Http\Resources;

use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignStopResource
 * @package App\Http\Resources
 *
 * @mixin CampaignStop
 */
class CampaignStopResource extends JsonResource
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
            'name' => $this->quest->name,
            'questUuid' => $this->quest->uuid,
            'compactQuest' => new CompactQuestResource($this->quest),
            'provinceUuid' => $this->province->uuid,
            'campaignUuid' => $this->campaign->uuid,
            'sideQuestUuids' => $this->sideQuests->map(function (SideQuest $sideQuest) {
                return $sideQuest->uuid;
            })->values()->toArray()
        ];
    }
}
