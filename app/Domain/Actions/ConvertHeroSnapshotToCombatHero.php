<?php


namespace App\Domain\Actions;


use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\AttackSnapshot;
use App\Domain\Models\HeroSnapshot;
use App\Domain\Models\ItemSnapshot;
use App\Domain\Models\MeasurableType;

class ConvertHeroSnapshotToCombatHero
{
    protected ConvertAttackSnapshotToCombatAttack $convertAttackSnapshotToCombatAttack;

    public function __construct(ConvertAttackSnapshotToCombatAttack $convertAttackSnapshotToCombatAttack)
    {
        $this->convertAttackSnapshotToCombatAttack = $convertAttackSnapshotToCombatAttack;
    }

    /**
     * @param HeroSnapshot $heroSnapshot
     * @return CombatHero
     */
    public function execute(HeroSnapshot $heroSnapshot)
    {
        $combatAttacks = new CombatAttackCollection();

        $heroSnapshot->itemSnapshots->each(function (ItemSnapshot $itemSnapshot) use ($combatAttacks) {
            $itemSnapshot->attackSnapshots->each(function (AttackSnapshot $attackSnapshot) use ($combatAttacks) {
                $combatAttacks->push($this->convertAttackSnapshotToCombatAttack->execute($attackSnapshot));
            });
        });

        return new CombatHero(
            $heroSnapshot->uuid,
            $heroSnapshot->getMeasurableSnapshot(MeasurableType::HEALTH)->final_amount,
            $heroSnapshot->getMeasurableSnapshot(MeasurableType::STAMINA)->final_amount,
            $heroSnapshot->getMeasurableSnapshot(MeasurableType::MANA)->final_amount,
            $heroSnapshot->protection,
            $heroSnapshot->block_chance,
            $heroSnapshot->combat_position_id,
            $combatAttacks
        );
    }
}
