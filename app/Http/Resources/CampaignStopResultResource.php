<?php

namespace App\Http\Resources;

use App\Domain\Models\CampaignStop;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignStopResultResource
 * @package App\Http\Resources
 *
 * @mixin CampaignStop
 */
class CampaignStopResultResource extends JsonResource
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
            'questName' => $this->quest->name,
            'sideQuestResults' => SideQuestResultResource::collection($this->sideQuestResults)
        ];
    }
}
