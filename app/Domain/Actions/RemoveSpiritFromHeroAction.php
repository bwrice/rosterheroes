<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\HeroPlayerSpiritException;

class RemoveSpiritFromHeroAction extends HeroSpiritAction
{
    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    public function execute(Hero $hero, PlayerSpirit $playerSpirit): Hero
    {
        $this->validateWeek($hero, $playerSpirit);
        $this->validateGameTime($hero, $playerSpirit);
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($hero->uuid);
        $heroAggregate->updatePlayerSpirit(null)->persist();
        return $hero->fresh();
    }
}