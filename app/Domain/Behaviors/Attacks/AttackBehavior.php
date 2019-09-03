<?php


namespace App\Domain\Behaviors\Attacks;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorInterface;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorInterface;
use App\Domain\Behaviors\TargetRanges\TargetRangeBehaviorInterface;
use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;

class AttackBehavior implements AdjustsCombatSpeed, AdjustsBaseDamage
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

    public function adjustBaseSpeed(float $speed): float
    {
        $speed = $this->damageTypeBehavior->adjustBaseSpeed($speed);
        $speed = $this->targetRangeBehavior->adjustBaseSpeed($speed);
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $baseDamage = $this->damageTypeBehavior->adjustBaseDamage($baseDamage);
        $baseDamage = $this->targetRangeBehavior->adjustBaseDamage($baseDamage);
        return $baseDamage;
    }
}
