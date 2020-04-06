<?php

namespace App\Http\Resources;

use App\Domain\Collections\SideQuestCollection;
use App\Domain\Models\Minion;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'level' => $this->level,
            'provinceID' => $this->province_id,
            'percent' => round($this->percent, 2),
            'sideQuests' => SideQuestResource::collection($this->sideQuests->sortBy(function (SideQuest $sideQuest) {
                return $sideQuest->difficulty();
            })),
            'titans' => TitanResource::collection($this->titans),
            'minions' => $this->minions->map(function (Minion $minion) {
                $resource = new MinionResource($minion);
                $minionCount = $this->getMinionCount($minion->pivot->weight);
                $resource->setCount($minionCount);
                return $resource;
            }),
        ];
    }
}
