<?php


namespace App\Domain\Combat;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Behaviors\TargetPriorities\TargetPriorityBehavior;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\Hero;

class HeroCombatAttack
{
    /**
     * @var int
     */
    protected $heroID;
    /**
     * @var int
     */
    protected $itemID;
    /**
     * @var int
     */
    protected $attackID;
    /**
     * @var CombatPositionBehavior
     */
    protected $attackerPositionBehavior;
    /**
     * @var CombatPositionBehavior
     */
    protected $targetPositionBehavior;
    /**
     * @var DamageTypeBehavior
     */
    protected $damageTypeBehavior;
    /**
     * @var TargetPriorityBehavior
     */
    protected $targetPriorityBehavior;
    /**
     * @var ResourceCostsCollection
     */
    protected $resourceCostsCollection;

    public function __construct(
        int $heroID,
        int $itemID,
        int $attackID,
        CombatPositionBehavior $attackerPositionBehavior,
        CombatPositionBehavior $targetPositionBehavior,
        DamageTypeBehavior $damageTypeBehavior,
        TargetPriorityBehavior $targetPriorityBehavior,
        ResourceCostsCollection $resourceCostsCollection)
    {
        $this->heroID = $heroID;
        $this->itemID = $itemID;
        $this->attackID = $attackID;
        $this->attackerPositionBehavior = $attackerPositionBehavior;
        $this->targetPositionBehavior = $targetPositionBehavior;
        $this->damageTypeBehavior = $damageTypeBehavior;
        $this->targetPriorityBehavior = $targetPriorityBehavior;
        $this->resourceCostsCollection = $resourceCostsCollection;
    }
}
