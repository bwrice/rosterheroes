<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class MinionCombatAttack extends CombatAttack
{
    /**
     * @var string
     */
    protected $minionUuid;
    /**
     * @var string
     */
    protected $combatantUuid;

    public function __construct(
        string $minionUuid,
        string $combatantUuid,
        string $name,
        string $attackUuid,
        int $damage,
        float $combatSpeed,
        int $tier,
        int $maxTargetsCount,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType)
    {
        $this->minionUuid = $minionUuid;
        $this->combatantUuid = $combatantUuid;
        parent::__construct(
            $name,
            $attackUuid,
            $damage,
            $combatSpeed,
            $tier,
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
            'minionUuid' => $this->minionUuid,
            'combatantUuid' => $this->combatantUuid
        ], parent::toArray());
    }

    /**
     * @return string
     */
    public function getCombatantUuid(): string
    {
        return $this->combatantUuid;
    }
}
