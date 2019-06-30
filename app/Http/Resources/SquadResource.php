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
            'spirit_essence' => $this->spirit_essence,
            'heroPosts' => HeroPostResource::collection($this->heroPosts->loadMissing([
                'hero.playerSpirit.game.homeTeam',
                'hero.playerSpirit.game.awayTeam',
                'hero.playerSpirit.player.team',
                'hero.playerSpirit.player.positions',
            ]))
        ];
    }
}
