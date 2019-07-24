<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;

class RemoveSpiritFromHeroAction
{
    /**
     * @var Hero
     */
    private $hero;
    /**
     * @var PlayerSpirit
     */
    private $playerSpirit;

    public function __construct(Hero $hero, PlayerSpirit $playerSpirit)
    {
        $this->hero = $hero;
        $this->playerSpirit = $playerSpirit;
    }

    public function __invoke(): Hero
    {
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($this->hero->uuid);
        $heroAggregate->updatePlayerSpirit(null)->persist();
        return $this->hero->fresh();
    }
}