<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 1/17/19
 * Time: 9:14 PM
 */

namespace App\Domain\Collections;


use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class HeroPostCollection extends Collection
{

    /**
     * @param HeroRace $heroRace
     * @return HeroPostCollection
     */
    public function filterByHeroRace(HeroRace $heroRace)
    {
        return $this->loadMissing('heroPostType.heroRaces')->filter(function (HeroPost $heroPost) use ($heroRace) {
            return in_array($heroRace->id, $heroPost->heroPostType->heroRaces->pluck('id')->toArray());
        });
    }

    /**
     * @param bool $filled
     * @return HeroPostCollection
     */
    public function postFilled($filled = true)
    {
        return $this->filter(function (HeroPost $heroPost) use ($filled) {
            return $filled ? $heroPost->hero_id : ! $heroPost->hero_id;
        });
    }

    public function heroRaces()
    {
        $heroRaces = new HeroRaceCollection();

        $this->loadMissing('heroPostType.heroRaces')->each(function (HeroPost $heroPost) use ($heroRaces) {
            $heroRaces->merge($heroPost->heroPostType->heroRaces);
        });

        return $heroRaces;
    }

    public function postEmpty()
    {
        return $this->filter(function (HeroPost $heroPost) {
            return is_null($heroPost->getHero());
        });
    }

    public function fillHeroes(HeroCollection $heroes)
    {
        return $this->loadMissing('heroPostType.heroRaces')->sortByDesc(function (HeroPost $heroPost) {
            return $heroPost->heroPostType->heroRaces->count();
        })->setHeroes($heroes);
    }

    protected function setHeroes(HeroCollection $heroes)
    {
        $heroes->each(function (Hero $hero) {
            /** @var HeroPost $heroPost */
            $heroPost = $this->postEmpty()->filterByHeroRace($hero->heroRace)->first();
            if (! $heroPost) {
                Log::warning("Couldn't fill hero post for hero", [
                    'hero' => $hero,
                    'heroPosts' => $this,
                    'emptyPosts' => $this->postEmpty()
                ]);
            } else {
                $heroPost->setHero($hero);
            }
        });
        return $this;
    }
}
