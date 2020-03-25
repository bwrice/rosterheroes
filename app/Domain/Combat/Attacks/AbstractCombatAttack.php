<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractCombatAttack implements CombatAttackInterface, Arrayable
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $attackUuid;
    /**
     * @var int
     */
    protected $damage;
    /**
     * @var float
     */
    protected $combatSpeed;
    /**
     * @var int
     */
    protected $tier;
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
        string $attackUuid,
        int $damage,
        float $combatSpeed,
        int $tier,
        CombatPosition $attackerPosition,
        CombatPosition $targetPosition,
        TargetPriority $targetPriority,
        DamageType $damageType,
        int $maxTargetsCount)
    {
        $this->name = $name;
        $this->attackUuid = $attackUuid;
        $this->damage = $damage;
        $this->combatSpeed = $combatSpeed;
        $this->tier = $tier;
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

    public function toArray()
    {
        return [
            'name' => $this->name,
            'attackUuid' => $this->attackUuid,
            'damage' => $this->damage,
            'combatSpeed' => $this->combatSpeed,
            'grade' => $this->tier,
            'attackerPositionID' => $this->attackerPosition->id,
            'targetPositionID' => $this->targetPosition->id,
            'targetPriorityID' => $this->targetPriority->id,
            'damageTypeID' => $this->damageType->id,
            'maxTargetsCount' => $this->maxTargetsCount
        ];
    }

    /**
     * @return string
     */
    public function getAttackUuid()
    {
        return $this->attackUuid;
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
     * @return int
     */
    public function getTier(): int
    {
        return $this->tier;
    }

    /**
     * @return CombatPosition
     */
    public function getAttackerPosition(): CombatPosition
    {
        return $this->attackerPosition;
    }

    /**
     * @return CombatPosition
     */
    public function getTargetPosition(): CombatPosition
    {
        return $this->targetPosition;
    }

    /**
     * @return TargetPriority
     */
    public function getTargetPriority(): TargetPriority
    {
        return $this->targetPriority;
    }

    /**
     * @return DamageType
     */
    public function getDamageType(): DamageType
    {
        return $this->damageType;
    }

    /**
     * @return int
     */
    public function getMaxTargetsCount(): int
    {
        return $this->maxTargetsCount;
    }
}
