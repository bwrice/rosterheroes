<?php

namespace App\Http\Resources;

use App\Domain\Models\Campaign;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignResource
 * @package App\Http\Resources
 *
 * @mixin Campaign
 */
class CampaignResource extends JsonResource
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
            'squadID' => $this->squad_id,
            'weekID' => $this->week_id,
            'continentID' => $this->continent_id,
            'campaignStops' => CampaignStopResource::collection($this->campaignStops)
        ];
    }
}
