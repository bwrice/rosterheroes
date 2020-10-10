<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Facades\DamageTypeFacade;

class CombatAttack implements CombatAttackInterface
{
    protected string $name, $sourceUuid, $attackerUuid;
    protected int $damage, $tier, $attackerPositionID, $targetPositionID, $targetPriorityID, $damageTypeID;
    protected ?int $targetsCount;
    protected float $combatSpeed;
    protected ResourceCostsCollection $resourceCosts;

    public function __construct(
        string $name,
        string $sourceUuid,
        string $attackerUuid,
        int $damage,
        float $combatSpeed,
        int $tier,
        ?int $targetsCount,
        int $attackerPositionID,
        int $targetPositionID,
        int $targetPriorityID,
        int $damageTypeID,
        ResourceCostsCollection $resourceCosts)
    {
        $this->name = $name;
        $this->sourceUuid = $sourceUuid;
        $this->attackerUuid = $attackerUuid;
        $this->damage = $damage;
        $this->combatSpeed = $combatSpeed;
        $this->tier = $tier;
        $this->targetsCount = $targetsCount;
        $this->attackerPositionID = $attackerPositionID;
        $this->targetPositionID = $targetPositionID;
        $this->targetPriorityID = $targetPriorityID;
        $this->damageTypeID = $damageTypeID;
        $this->resourceCosts = $resourceCosts;
    }

    /**
     * @return string
     */
    public function getSourceUuid()
    {
        return $this->sourceUuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getDamage(): int
    {
        return $this->damage;
    }

    /**
     * @return float
     */
    public function getCombatSpeed(): float
    {
        return $this->combatSpeed;
    }

    /**
     * @return int|null
     */
    public function getTargetsCount(): ?int
    {
        return $this->targetsCount;
    }

    /**
     * @return int
     */
    public function getTier(): int
    {
        return $this->tier;
    }

    /**
     * @return int
     */
    public function getAttackerPositionID(): int
    {
        return $this->attackerPositionID;
    }

    /**
     * @return int
     */
    public function getTargetPriorityID(): int
    {
        return $this->targetPriorityID;
    }

    /**
     * @return int
     */
    public function getDamageTypeID(): int
    {
        return $this->damageTypeID;
    }

    /**
     * @return ResourceCostsCollection
     */
    public function getResourceCosts(): ResourceCostsCollection
    {
        return $this->resourceCosts;
    }

    /**
     * @return string
     */
    public function getAttackerUuid(): string
    {
        return $this->attackerUuid;
    }

    /**
     * @return int
     */
    public function getTargetPositionID(): int
    {
        return $this->targetPositionID;
    }
}
