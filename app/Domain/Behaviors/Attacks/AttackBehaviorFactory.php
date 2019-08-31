<?php


namespace App\Domain\Behaviors\Attacks;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorFactory;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorFactory;
use App\Domain\Behaviors\TargetRanges\TargetRangeBehaviorFactory;
use App\Domain\Models\Attack;

class AttackBehaviorFactory
{
    /**
     * @var DamageTypeBehaviorFactory
     */
    private $damageTypeBehaviorFactory;
    /**
     * @var TargetRangeBehaviorFactory
     */
    private $targetRangeBehaviorFactory;
    /**
     * @var TargetPriorityBehaviorFactory
     */
    private $targetPriorityBehaviorFactory;

    public function __construct(
        DamageTypeBehaviorFactory $damageTypeBehaviorFactory,
        TargetRangeBehaviorFactory $targetRangeBehaviorFactory,
        TargetPriorityBehaviorFactory $targetPriorityBehaviorFactory)
    {
        $this->damageTypeBehaviorFactory = $damageTypeBehaviorFactory;
        $this->targetRangeBehaviorFactory = $targetRangeBehaviorFactory;
        $this->targetPriorityBehaviorFactory = $targetPriorityBehaviorFactory;
    }

    /**
     * @param Attack $attack
     * @return AttackBehavior
     */
    public function getAttackBehavior(Attack $attack): AttackBehavior
    {
        $damageTypeBehavior = $this->damageTypeBehaviorFactory->getBehavior($attack->damageType->name);
        $targetRangeBehavior = $this->targetRangeBehaviorFactory->getBehavior($attack->targetRange->name);
        $targetPriorityBehavior = $this->targetPriorityBehaviorFactory->getBehavior($attack->targetPriority->name);
        return new AttackBehavior($damageTypeBehavior, $targetRangeBehavior, $targetPriorityBehavior);
    }
}
