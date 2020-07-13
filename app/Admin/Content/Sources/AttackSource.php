<?php


namespace App\Admin\Content\Sources;



use App\Domain\Models\Attack;

class AttackSource
{
    /**
     * @var string
     */
    protected $uuid;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $attackerPositionID;
    /**
     * @var int
     */
    protected $targetPositionID;
    /**
     * @var int
     */
    protected $targetPriorityID;
    /**
     * @var int
     */
    protected $damageTypeID;
    /**
     * @var int
     */
    protected $tier;
    /**
     * @var int|null
     */
    protected $targetsCount;

    public function __construct(
        string $uuid,
        string $name,
        int $attackerPositionID,
        int $targetPositionID,
        int $targetPriorityID,
        int $damageTypeID,
        int $tier,
        ?int $targetsCount)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->attackerPositionID = $attackerPositionID;
        $this->targetPositionID = $targetPositionID;
        $this->targetPriorityID = $targetPriorityID;
        $this->damageTypeID = $damageTypeID;
        $this->tier = $tier;
        $this->targetsCount = $targetsCount;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
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
    public function getAttackerPositionID(): int
    {
        return $this->attackerPositionID;
    }

    /**
     * @return int
     */
    public function getTargetPositionID(): int
    {
        return $this->targetPositionID;
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
     * @return int
     */
    public function getTier(): int
    {
        return $this->tier;
    }

    /**
     * @return int|null
     */
    public function getTargetsCount(): ?int
    {
        return $this->targetsCount;
    }

    /**
     * @param string $name
     * @return AttackSource
     */
    public function setName(string $name): AttackSource
    {
        $this->name = $name;
        return $this;
    }

    public function isSynced(Attack $attack)
    {
        if ($attack->name !== $this->getName()) {
            return false;
        }
        if ($attack->attacker_position_id !== $this->getAttackerPositionID()) {
            return false;
        }
        if ($attack->target_position_id !== $this->getTargetPositionID()) {
            return false;
        }
        if ($attack->target_priority_id !== $this->getTargetPriorityID()) {
            return false;
        }
        if ($attack->damage_type_id !== $this->getDamageTypeID()) {
            return false;
        }
        if ($attack->tier !== $this->getTier()) {
            return false;
        }
        if ($attack->targets_count !== $this->getTargetsCount()) {
            return false;
        }
        return true;
    }
}