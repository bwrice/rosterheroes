<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 2/3/19
 * Time: 8:40 AM
 */

namespace App\Domain\Actions;


use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Collections\HeroPostCollection;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\Squad;
use App\Squads\HeroClassAvailability;
use App\Squads\HeroPostAvailability;

class AddHeroToSquad
{
    /**
     * @var \App\Domain\Models\Squad
     */
    private $squad;
    /**
     * @var \App\Domain\Models\HeroRace
     */
    private $heroRace;
    /**
     * @var HeroClass
     */
    private $heroClass;

    private $heroName;

    public function __construct(
        Squad $squad,
        HeroRace $heroRace,
        HeroClass $heroClass,
        $heroName
    )
    {
        $this->squad = $squad;
        $this->heroRace = $heroRace;
        $this->heroClass = $heroClass;
        $this->heroName = $heroName;
    }

    /**
     * @return Hero
     * @throws HeroPostNotFoundException
     * @throws InvalidHeroClassException
     */
    public function __invoke()
    {
        $heroPost = $this->squad->getHeroPostAvailability()->heroRace($this->heroRace)->first();
        if (! $heroPost) {
            throw new HeroPostNotFoundException($this->heroRace);
        }

        if (! $this->squad->getHeroClassAvailability()->contains($this->heroClass)) {
            throw new InvalidHeroClassException($this->heroClass);
        }

        $hero = Hero::createWithAttributes([
            'name' => $this->heroName,
            'hero_class_id' => $this->heroClass->id,
            'hero_rank_id' => HeroRank::getStarting()->id,
            'hero_race_id' => $this->heroRace->id
        ]);

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        $hero->addStartingSlots();
        $hero->addStartingMeasurables();
        return $hero->fresh();
    }
}