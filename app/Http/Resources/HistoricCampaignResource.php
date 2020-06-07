<?php

namespace App\Http\Resources;

use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class HistoricCampaignResource
 * @package App\Http\Resources
 *
 * @mixin Campaign
 */
class HistoricCampaignResource extends JsonResource
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
            'description' => $this->getDescription(),
            'stops' => $this->campaignStops->map(function (CampaignStop $campaignStop) {
                return [
                    'uuid' => $campaignStop->uuid,
                    'questName' => $campaignStop->quest->name
                ];
            })
        ];
    }
}
