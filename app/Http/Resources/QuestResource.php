<?php

namespace App\Http\Resources;

use App\Domain\Collections\SkirmishCollection;
use App\Domain\Models\Quest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class QuestResource
 * @package App\Http\Resources
 *
 * @mixin Quest
 */
class QuestResource extends JsonResource
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
            'name' => $this->name,
            'level' => $this->level,
            'skirmishes' => SkirmishResource::collection($this->skirmishes),
            'provinceID' => $this->province_id
        ];
    }
}
