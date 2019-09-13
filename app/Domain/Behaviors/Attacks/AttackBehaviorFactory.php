<?php


namespace App\Domain\Behaviors\Attacks;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehaviorFactory;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehaviorFactory;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehaviorFactory;
use App\Domain\Models\Attack;

class AttackBehaviorFactory
{
    /**
     * @var DamageTypeBehaviorFactory
     */
    private $damageTypeBehaviorFactory;
    /**
     * @var CombatPositionBehaviorFactory
     */
    private $combatPositionBehaviorFactory;
    /**
     * @var TargetPriorityBehaviorFactory
     */
    private $targetPriorityBehaviorFactory;

    public function __construct(
        DamageTypeBehaviorFactory $damageTypeBehaviorFactory,
        CombatPositionBehaviorFactory $combatPositionBehaviorFactory,
        TargetPriorityBehaviorFactory $targetPriorityBehaviorFactory)
    {
        $this->damageTypeBehaviorFactory = $damageTypeBehaviorFactory;
        $this->combatPositionBehaviorFactory = $combatPositionBehaviorFactory;
        $this->targetPriorityBehaviorFactory = $targetPriorityBehaviorFactory;
    }

    /**
     * @param Attack $attack
     * @return AttackBehavior
     */
    public function getAttackBehavior(Attack $attack): AttackBehavior
    {
        $damageTypeBehavior = $this->damageTypeBehaviorFactory->getBehavior($attack->damageType->name);
        $targetRangeBehavior = $this->combatPositionBehaviorFactory->getBehavior($attack->attackerPosition->name);
        $targetPriorityBehavior = $this->targetPriorityBehaviorFactory->getBehavior($attack->targetPriority->name);
        return new AttackBehavior($damageTypeBehavior, $targetRangeBehavior, $targetPriorityBehavior);
    }
}
