<?php


namespace App\Factories\Combat;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Attacks\HeroCombatAttack;

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
