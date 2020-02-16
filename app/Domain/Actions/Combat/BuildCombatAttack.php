<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\CombatAttack;
use App\Domain\Interfaces\HasFantasyPoints;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Services\FantasyPower;
use Illuminate\Database\Eloquent\Collection;

class BuildCombatAttack
{
    /**
     * @var FantasyPower
     */
    protected $fantasyPower;

    public function __construct(FantasyPower $fantasyPower)
    {
        $this->fantasyPower = $fantasyPower;
    }

    public function execute(
        Attack $attack,
        HasFantasyPoints $hasFantasyPoints,
        Collection $combatPositions = null,
        Collection $targetPriorities = null,
        Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        $attackerPosition = $combatPositions->find($attack->attacker_position_id);
        $targetPosition = $combatPositions->find($attack->target_position_id);
        $targetPriority = $targetPriorities->find($attack->target_priority_id);
        $damageType = $damageTypes->find($attack->damage_type_id);
        $damage = $this->calculateAttackDamage($attack, $hasFantasyPoints);
        return new CombatAttack(
            $attack->name,
            $attack->id,
            $damage,
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $attack->getMaxTargetsCount()
        );
    }

    protected function calculateAttackDamage(Attack $attack, HasFantasyPoints $hasFantasyPoints)
    {
        $fantasyPower = $this->fantasyPower->calculate($hasFantasyPoints->getFantasyPoints());
        $baseDamage = $attack->getBaseDamage();
        $damageMultiplier = $attack->getDamageMultiplier();
        return (int) max(ceil($baseDamage + ($damageMultiplier * $fantasyPower)), 1);
    }
}
