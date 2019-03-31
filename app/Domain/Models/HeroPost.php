<?php

namespace App\Domain\Models;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Player;
use App\Domain\Collections\HeroPostCollection;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroPost
 * @package App\Heroes\HeroPosts
 *
 * @property int $squad_id
 * @property int $hero_race_id
 * @property int|null $hero_id
 *
 * @property Squad $squad
 * @property \App\Domain\Models\Hero|null $hero
 * @property HeroRace $heroRace
 */
class HeroPost extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new HeroPostCollection($models);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }

    public function heroRace()
    {
        return $this->belongsTo(HeroRace::class);
    }

//    public function hasPosition(Player $player)
//    {
//        return $this->heroRace->positions->intersect($player->positions)->count() > 0;
//    }

    public function getPositions()
    {
        return $this->heroRace->positions;
    }
}
