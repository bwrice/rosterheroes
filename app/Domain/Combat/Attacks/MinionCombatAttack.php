<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class MinionCombatAttack extends AbstractCombatAttack
{
    /**
     * @var string
     */
    protected $minionUuid;

    public function __construct(
        string $minionUuid,
        string $name,
        string $attackUuid,
        int $damage,
        float $combatSpeed,
        int $grade,
        int $maxTargetsCount,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType)
    {
        $this->minionUuid = $minionUuid;
        parent::__construct(
            $name,
            $attackUuid,
            $damage,
            $combatSpeed,
            $grade,
            $attackerPosition,
            $targetPosition,
            $targetPriority,
            $damageType,
            $maxTargetsCount
        );
    }

    /**
     * @return string
     */
    public function getMinionUuid(): string
    {
        return $this->minionUuid;
    }

    public function toArray()
    {
        return array_merge([
            'minionUuid' => $this->minionUuid
        ], parent::toArray());
    }
}
