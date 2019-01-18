<?php

namespace App\Heroes\HeroPosts;

use App\Hero;
use App\HeroRace;
use App\Squad;
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
 * @property Hero|null $hero
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
}
