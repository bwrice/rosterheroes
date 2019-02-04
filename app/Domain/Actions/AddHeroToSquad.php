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
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
use App\Heroes\HeroPosts\HeroPostCollection;
use App\HeroRace;
use App\HeroRank;
use App\Squad;
use App\Squads\HeroClassAvailability;
use App\Squads\HeroPostAvailability;

class AddHeroToSquad
{
    /**
     * @var HeroClassAvailability
     */
    private $heroClassAvailability;
    /**
     * @var Squad
     */
    private $squad;
    /**
     * @var HeroRace
     */
    private $heroRace;
    /**
     * @var HeroClass
     */
    private $heroClass;

    private $heroName;
    /**
     * @var HeroPostAvailability
     */
    private $heroPostAvailability;

    public function __construct(
        HeroClassAvailability $heroClassAvailability,
        HeroPostAvailability $heroPostAvailability,
        Squad $squad,
        HeroRace $heroRace,
        HeroClass $heroClass,
        $heroName
    )
    {
        $this->heroClassAvailability = $heroClassAvailability;
        $this->squad = $squad;
        $this->heroRace = $heroRace;
        $this->heroClass = $heroClass;
        $this->heroName = $heroName;
        $this->heroPostAvailability = $heroPostAvailability;
    }

    /**
     * @return Hero
     * @throws HeroPostNotFoundException
     * @throws InvalidHeroClassException
     */
    public function execute()
    {
        $heroPost = $this->heroPostAvailability->get($this->squad)->heroRace($this->heroRace)->first();
        if (! $heroPost) {
            throw new HeroPostNotFoundException($this->heroRace);
        }

        if (! $this->heroClassAvailability->get($this->squad)->contains($this->heroClass)) {
            throw new InvalidHeroClassException($this->heroClass);
        }

        $hero = Hero::createWithAttributes([
            'name' => $this->heroName,
            'hero_class_id' => $this->heroClass->id,
            'hero_rank_id' => HeroRank::getStarting()->id
        ]);

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        $hero->addStartingSlots();
        $hero->addStartingMeasurables();
        return $hero->fresh();
    }
}