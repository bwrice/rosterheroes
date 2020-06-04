<?php

namespace App\Http\Resources;

use App\Domain\Models\Squad;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SquadResource
 * @package App\Http\Resources
 *
 * @mixin Squad
 */
class SquadResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'spiritEssence' => $this->spirit_essence,
            'gold' => $this->gold,
            'level' => $this->level(),
            'experience' => $this->experience,
            'experienceOverLevel' => $this->experienceOverLevel(),
            'experienceUntilNextLevel' => $this->experienceUntilNextLevel(),
            'favor' => $this->favor,
            'questsPerWeek' => $this->getQuestsPerWeek(),
            'sideQuestsPerQuest' => $this->getSideQuestsPerQuest()
        ];
    }
}
