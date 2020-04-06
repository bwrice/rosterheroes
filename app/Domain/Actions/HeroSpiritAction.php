<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\HeroPlayerSpiritException;

abstract class HeroSpiritAction
{
    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    abstract public function execute(Hero $hero, PlayerSpirit $playerSpirit): Hero;

    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @throws HeroPlayerSpiritException
     */
    protected function validateGameTime(Hero $hero, PlayerSpirit $playerSpirit): void
    {
        if ($playerSpirit->playerGameLog->game->hasStarted()) {
            $message = $playerSpirit->playerGameLog->game->getSimpleDescription() . " has already started";
            throw new HeroPlayerSpiritException(
                $hero,
                $playerSpirit,
                $message,
                HeroPlayerSpiritException::GAME_STARTED
            );
        }
    }


    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @throws HeroPlayerSpiritException
     */
    protected function validateWeek(Hero $hero, PlayerSpirit $playerSpirit): void
    {
        if (!Week::isCurrent($playerSpirit->week)) {
            $gameDescription = $playerSpirit->playerGameLog->game->getSimpleDescription();
            $message = $gameDescription . ' is not for the current week';
            throw new HeroPlayerSpiritException(
                $hero,
                $playerSpirit,
                $message,
                HeroPlayerSpiritException::INVALID_WEEK
            );
        }
    }
}
