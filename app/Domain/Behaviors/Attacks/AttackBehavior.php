<?php


namespace App\Domain\Behaviors\Attacks;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorInterface;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorInterface;
use App\Domain\Behaviors\TargetRanges\TargetRangeBehaviorInterface;

class AttackBehavior
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

    public function adjustCombatSpeed(float $speed)
    {
        $speed = $this->damageTypeBehavior->adjustCombatSpeed($speed);
        return $speed;
    }
}
