<?php


namespace App\Admin\Content\Sources;



use App\Domain\Models\Attack;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

class AttackSource implements Arrayable, Jsonable
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

    public static function build(string $name, int $attackerPositionID, int $targetPositionID, int $targetPriorityID, int $damageTypeID, int $tier, ?int $targetsCount)
    {
        return new static(
            Str::uuid(),
            $name,
            $attackerPositionID,
            $targetPositionID,
            $targetPriorityID,
            $damageTypeID,
            $tier,
            $targetsCount
        );
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

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'attacker_position_id' => $this->getAttackerPositionID(),
            'target_position_id' => $this->getTargetPositionID(),
            'target_priority_id' => $this->getTargetPriorityID(),
            'damage_type_id' => $this->getDamageTypeID(),
            'tier' => $this->getTier(),
            'targets_count' => $this->getTargetsCount()
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
