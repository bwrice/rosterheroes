<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroPost;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Squad
 * @package App\Http\Resources
 *
 * @mixin \App\Domain\Models\Squad
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
            'experience' => $this->experience,
            'favor' => $this->favor,
            'questsPerWeek' => $this->getQuestsPerWeek(),
            'sideQuestsPerQuest' => $this->getSideQuestsPerQuest()
        ];
    }
}
