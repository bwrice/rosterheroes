<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\HeroPlayerSpiritException;

class RemoveSpiritFromHeroAction
{
    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    public function execute(Hero $hero, PlayerSpirit $playerSpirit): Hero
    {
        $this->validateGameTime($hero, $playerSpirit);
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($hero->uuid);
        $heroAggregate->updatePlayerSpirit(null)->persist();
        return $hero->fresh();
    }

    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @throws HeroPlayerSpiritException
     */
    protected function validateGameTime(Hero $hero, PlayerSpirit $playerSpirit)
    {
        if ($playerSpirit->game->hasStarted()) {
            $message = $playerSpirit->game->getSimpleDescription() . " has already started";
            throw new HeroPlayerSpiritException(
                $hero,
                $playerSpirit,
                $message,
                HeroPlayerSpiritException::GAME_STARTED
            );
        }
    }
}