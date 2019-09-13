<?php


namespace App\Domain\Behaviors\Attacks;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorInterface;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorInterface;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorInterface;
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
     * @var CombatPositionBehaviorInterface
     */
    private $combatPositionBehavior;
    /**
     * @var TargetPriorityBehaviorInterface
     */
    private $targetPriorityBehavior;

    public function __construct(
        DamageTypeBehaviorInterface $damageTypeBehavior,
        CombatPositionBehaviorInterface $combatPositionBehavior,
        TargetPriorityBehaviorInterface $targetPriorityBehavior)
    {
        $this->damageTypeBehavior = $damageTypeBehavior;
        $this->combatPositionBehavior = $combatPositionBehavior;
        $this->targetPriorityBehavior = $targetPriorityBehavior;
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $speed = $this->damageTypeBehavior->adjustCombatSpeed($speed);
        $speed = $this->combatPositionBehavior->adjustCombatSpeed($speed);
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $baseDamage = $this->damageTypeBehavior->adjustBaseDamage($baseDamage);
        $baseDamage = $this->combatPositionBehavior->adjustBaseDamage($baseDamage);
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        $damageModifier = $this->damageTypeBehavior->adjustDamageMultiplier($damageModifier);
        $damageModifier = $this->combatPositionBehavior->adjustDamageMultiplier($damageModifier);
        return $damageModifier;
    }
}
