<?php


namespace App\Factories\Combat;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\CombatHero;
use App\Domain\Combat\HeroCombatAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class HeroCombatAttackFactory
{
    protected $combatAttackFactory;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $heroID = rand(1, 999999);
        $itemID = rand(1, 999999);
        $resourceCosts = new ResourceCostsCollection();
        $combatAttackFactory = $this->combatAttackFactory ?: CombatAttackFactory::new();
        $combatAttack = $combatAttackFactory->create();
        return new HeroCombatAttack(
            $heroID,
            $itemID,
            $combatAttack,
            $resourceCosts
        );
    }
    
    public function withCombatAttackFactory(CombatAttackFactory $combatAttackFactory)
    {
        $clone = clone $this;
        $clone->combatAttackFactory = $combatAttackFactory;
        return $clone;
    }
}
