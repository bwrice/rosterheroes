<?php

namespace App\Http\Resources;

use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;
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
            'name' => $this->quest->name,
            'questID' => $this->quest->id,
            'provinceID' => $this->province->id,
            'skirmishUuids' => $this->skirmishes->map(function (Skirmish $skirmish) {
                return $skirmish->uuid;
            })->values()->toArray()
        ];
    }
}
