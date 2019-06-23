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
            'salary' => $this->salary,
            'heroPosts' => HeroPostResource::collection($this->heroPosts->loadMissing([
                'hero.weeklyGamePlayer.game.homeTeam',
                'hero.weeklyGamePlayer.game.awayTeam',
                'hero.weeklyGamePlayer.player.team',
                'hero.weeklyGamePlayer.player.positions',
            ]))
        ];
    }
}
