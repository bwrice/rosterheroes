<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Hero;

class RemoveSpiritFromHeroAction
{
    /**
     * @var Hero
     */
    private $hero;

    public function __construct(Hero $hero)
    {
        $this->hero = $hero;
    }

    public function __invoke(): Hero
    {
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($this->hero->uuid);
        $heroAggregate->updatePlayerSpirit(null)->persist();
        return $this->hero->fresh();
    }
}