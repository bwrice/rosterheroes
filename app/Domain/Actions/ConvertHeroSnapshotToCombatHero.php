<?php


namespace App\Domain\Actions;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\HeroSnapshot;
use App\Domain\Models\MeasurableType;

class ConvertHeroSnapshotToCombatHero
{

    /**
     * @param HeroSnapshot $heroSnapshot
     * @return CombatHero
     */
    public function execute(HeroSnapshot $heroSnapshot)
    {
        return new CombatHero(
            $heroSnapshot->uuid,
            $heroSnapshot->getMeasurableSnapshot(MeasurableType::HEALTH)->final_amount,
            $heroSnapshot->getMeasurableSnapshot(MeasurableType::STAMINA)->final_amount,
            $heroSnapshot->getMeasurableSnapshot(MeasurableType::MANA)->final_amount,
            $heroSnapshot->protection,
            $heroSnapshot->block_chance,
            $heroSnapshot->combat_position_id,
            new CombatAttackCollection()
        );
    }
}
