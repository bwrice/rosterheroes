<?php


namespace App\Domain\Actions;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\Week;
use App\Exceptions\ChangeCombatPositionException;

class ChangeHeroCombatPositionAction
{
    /**
     * @param Hero $hero
     * @param CombatPosition $combatPosition
     * @return Hero
     */
    public function execute(Hero $hero, CombatPosition $combatPosition): Hero
    {
        $week = Week::current();
        if (! $week->adventuringOpen()) {
            throw new ChangeCombatPositionException($combatPosition, $hero, "Current week is locked", ChangeCombatPositionException::CODE_WEEK_LOCKED);
        }

        $hero->combat_position_id = $combatPosition->id;
        $hero->save();
        return $hero->fresh();
    }
}
