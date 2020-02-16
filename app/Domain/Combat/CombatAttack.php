<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class CombatAttack implements CombatAttackInterface
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $attackID;
    /**
     * @var int
     */
    protected $damage;
    /**
     * @var float
     */
    protected $combatSpeed;
    /**
     * @var CombatPosition
     */
    protected $attackerPosition;
    /**
     * @var CombatPosition
     */
    protected $targetPosition;
    /**
     * @var TargetPriority
     */
    protected $targetPriority;
    /**
     * @var DamageType
     */
    protected $damageType;
    /**
     * @var int
     */
    protected $maxTargetsCount;

    public function __construct(
        string $name,
        int $attackID,
        int $damage,
        float $combatSpeed,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType,
        int $maxTargetsCount)
    {
        $this->name = $name;
        $this->attackID = $attackID;
        $this->damage = $damage;
        $this->combatSpeed = $combatSpeed;
        $this->attackerPosition = $attackerPosition;
        $this->targetPosition = $targetPosition;
        $this->targetPriority = $targetPriority;
        $this->damageType = $damageType;
        $this->maxTargetsCount = $maxTargetsCount;
    }

    public function getDamagePerTarget(int $targetsCount): int
    {
        return $this->damageType->getBehavior()->getDamagePerTarget($this->damage, $targetsCount);
    }

    public function getTargets(CombatantCollection $possibleTargets): CombatantCollection
    {
        return $possibleTargets
            ->filterByCombatPosition($this->targetPosition)
            ->sortByTargetPriority($this->targetPriority)
            ->take($this->maxTargetsCount);
    }
}
