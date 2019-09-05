<?php


namespace App\Domain\Behaviors\Attacks;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorInterface;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorInterface;
use App\Domain\Behaviors\TargetRanges\TargetRangeBehaviorInterface;
use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;
use App\Domain\Interfaces\AdjustsDamageModifier;

class AttackBehavior implements AdjustsCombatSpeed, AdjustsBaseDamage, AdjustsDamageModifier
{
    /**
     * @var DamageTypeBehaviorInterface
     */
    private $damageTypeBehavior;
    /**
     * @var TargetRangeBehaviorInterface
     */
    private $targetRangeBehavior;
    /**
     * @var TargetPriorityBehaviorInterface
     */
    private $targetPriorityBehavior;

    public function __construct(
        DamageTypeBehaviorInterface $damageTypeBehavior,
        TargetRangeBehaviorInterface $targetRangeBehavior,
        TargetPriorityBehaviorInterface $targetPriorityBehavior)
    {
        $this->damageTypeBehavior = $damageTypeBehavior;
        $this->targetRangeBehavior = $targetRangeBehavior;
        $this->targetPriorityBehavior = $targetPriorityBehavior;
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $speed = $this->damageTypeBehavior->adjustCombatSpeed($speed);
        $speed = $this->targetRangeBehavior->adjustCombatSpeed($speed);
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $baseDamage = $this->damageTypeBehavior->adjustBaseDamage($baseDamage);
        $baseDamage = $this->targetRangeBehavior->adjustBaseDamage($baseDamage);
        return $baseDamage;
    }

    public function adjustDamageModifier(float $damageModifier): float
    {
        $damageModifier = $this->damageTypeBehavior->adjustDamageModifier($damageModifier);
        $damageModifier = $this->targetRangeBehavior->adjustDamageModifier($damageModifier);
        return $damageModifier;
    }
}
