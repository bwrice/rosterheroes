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
     * @var \App\Domain\Models\Squad
     */
    private $squad;
    /**
     * @var CreateNewHeroAction
     */
    private $createNewHeroAction;

    public function __construct(Squad $squad, CreateNewHeroAction $createNewHeroAction)
    {
        $this->squad = $squad;
        $this->createNewHeroAction = $createNewHeroAction;
    }

    /**
     * @return Hero
     * @throws HeroPostNotFoundException
     * @throws InvalidHeroClassException
     */
    public function __invoke(): Hero
    {
        $heroRace = $this->createNewHeroAction->getHeroRace();
        $heroPost= $this->squad->getHeroPostAvailability()->heroRace($heroRace)->first();
        if (! $heroPost) {
            throw new HeroPostNotFoundException($heroRace);
        }

        $heroClass = $this->createNewHeroAction->getHeroClass();
        if (! $this->squad->getHeroClassAvailability()->contains($heroClass)) {
            throw new InvalidHeroClassException($heroClass);
        }

        // invoke the CreateNewHeroAction
        $hero = call_user_func($this->createNewHeroAction);

        $heroPost->hero_id = $hero->id;
        $heroPost->save();
        return $hero->fresh();
    }
}