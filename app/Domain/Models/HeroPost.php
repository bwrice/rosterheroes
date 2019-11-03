<?php

namespace App\Domain\Models;

use App\Domain\Collections\HeroPostCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroPost
 * @package App\Heroes\HeroPosts
 *
 * @property int $squad_id
 * @property int|null $hero_id
 * @property int $hero_post_type_id
 *
 * @property Squad $squad
 * @property \App\Domain\Models\Hero|null $hero
 * @property HeroPostType $heroPostType
 */
class HeroPost extends Model
{
    protected $guarded = [];

    /** @var Hero|null */
    public $hero;

    public function newCollection(array $models = [])
    {
        return new HeroPostCollection($models);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function heroPostType()
    {
        return $this->belongsTo(HeroPostType::class);
    }

    public function getHeroRaces()
    {
        return $this->heroPostType->heroRaces;
    }

    /**
     * @param Hero|null $hero
     * @return HeroPost
     */
    public function setHero(?Hero $hero): HeroPost
    {
        $this->hero = $hero;
        return $this;
    }

    /**
     * @return Hero|null
     */
    public function getHero(): ?Hero
    {
        return $this->hero;
    }
}
