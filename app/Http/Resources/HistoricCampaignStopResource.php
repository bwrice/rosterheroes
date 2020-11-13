<?php

namespace App\Http\Resources;

use App\Domain\Models\CampaignStop;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HistoricCampaignStopResource
 * @package App\Http\Resources
 *
 * @mixin CampaignStop
 */
class HistoricCampaignStopResource extends JsonResource
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
            'provinceUuid' => $this->province->uuid,
            'sideQuestResults' => SideQuestResultResource::collection($this->sideQuestResults)
        ];
    }
}
