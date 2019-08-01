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

class AddNewHeroToSquadAction
{
    /**
     * @var CreateHeroAction
     */
    private $createHeroAction;

    public function __construct(CreateHeroAction $createHeroAction)
    {
        $this->createHeroAction = $createHeroAction;
    }

    /**
     * @param Squad $squad
     * @param string $heroName
     * @param HeroClass $heroClass
     * @param HeroRace $heroRace
     * @return Hero|null
     * @throws HeroPostNotFoundException
     * @throws InvalidHeroClassException
     */
    public function execute(Squad $squad, string $heroName, HeroClass $heroClass, HeroRace $heroRace)
    {
        $heroPost= $squad->getHeroPostAvailability()->heroRace($heroRace)->first();
        if (! $heroPost) {
            throw new HeroPostNotFoundException($heroRace);
        }

        if (! $squad->getHeroClassAvailability()->contains($heroClass)) {
            throw new InvalidHeroClassException($heroClass);
        }

        // invoke the CreateNewHeroAction
        $hero = $this->createHeroAction->execute($heroName, $heroClass, $heroRace, HeroRank::getStarting());

        $heroPost->hero_id = $hero->id;
        $heroPost->save();
        return $hero->fresh();
    }
}