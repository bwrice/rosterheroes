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
            'provinceUuid' => $this->province->uuid,
            'campaignUuid' => $this->campaign->uuid,
            'skirmishUuids' => $this->skirmishes->map(function (SideQuest $skirmish) {
                return $skirmish->uuid;
            })->values()->toArray()
        ];
    }
}
