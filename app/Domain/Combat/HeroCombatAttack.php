<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class HeroCombatAttack implements CombatAttackInterface
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $heroID;
    /**
     * @var int
     */
    protected $itemID;
    /**
     * @var int
     */
    protected $attackID;
    /**
     * @var int
     */
    protected $damage;
    /**
     * @var int
     */
    protected $grade;
    /**
     * @var float
     */
    protected $speed;
    /**
     * @var CombatPosition
     */
    protected $attackerPosition;
    /**
     * @var CombatPosition
     */
    protected $targetPosition;
    /**
     * @var DamageType
     */
    protected $damageType;
    /**
     * @var TargetPriority
     */
    protected $targetPriority;
    /**
     * @var ResourceCostsCollection
     */
    protected $resourceCostsCollection;
    /**
     * @var int|null
     */
    protected $maxTargetsCount;

    public function __construct(
        string $name,
        int $heroID,
        int $itemID,
        int $attackID,
        int $damage,
        int $grade,
        float $speed,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType,
        ResourceCostsCollection $resourceCostsCollection,
        int $maxTargetsCount)
    {
        $this->name = $name;
        $this->heroID = $heroID;
        $this->itemID = $itemID;
        $this->attackID = $attackID;
        $this->damage = $damage;
        $this->grade = $grade;
        $this->speed = $speed;
        $this->attackerPosition = $attackerPosition;
        $this->targetPosition = $targetPosition;
        $this->damageType = $damageType;
        $this->targetPriority = $targetPriority;
        $this->resourceCostsCollection = $resourceCostsCollection;
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
