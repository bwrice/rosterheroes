<?php


namespace App\Domain\Actions;


use App\AttackSnapshot;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Interfaces\HasAttackSnapshots;
use App\Domain\Models\Attack;
use Illuminate\Support\Str;

class BuildAttackSnapshot
{
    /**
     * @var CalculateCombatDamage
     */
    protected $calculateCombatDamage;

    public function __construct(CalculateCombatDamage $calculateCombatDamage)
    {
        $this->calculateCombatDamage = $calculateCombatDamage;
    }

    /**
     * @param Attack $attack
     * @param HasAttackSnapshots $hasAttackSnapshots
     * @param float $fantasyPower
     * @return AttackSnapshot
     */
    public function execute(Attack $attack, HasAttackSnapshots $hasAttackSnapshots, float $fantasyPower)
    {
        /** @var AttackSnapshot $attackSnapshot */
        $attackSnapshot = $hasAttackSnapshots->attackSnapshots()->create([
            'uuid' => Str::uuid(),
            'attack_id' => $attack->id,
            'damage' => $this->calculateCombatDamage->execute($attack, $fantasyPower),
            'combat_speed' => $attack->getCombatSpeed(),
            'name' => $attack->name,
            'attacker_position_id' => $attack->attacker_position_id,
            'target_position_id' => $attack->target_priority_id,
            'damage_type_id' => $attack->damage_type_id,
            'target_priority_id' => $attack->target_priority_id,
            'tier' => $attack->tier,
            'targets_count' => $attack->targets_count
        ]);

        return $attackSnapshot;
    }
}
