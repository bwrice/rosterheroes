<?php


namespace App\Factories\Combat;

use App\Domain\Combat\Attacks\MinionCombatAttack;

class MinionCombatAttackFactory
{
    protected $combatAttackFactory;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $minionID = rand(1, 999999);
        $combatAttackFactory = $this->combatAttackFactory ?: CombatAttackFactory::new();
        $combatAttack = $combatAttackFactory->create();
        return new MinionCombatAttack(
            $minionID,
            $combatAttack
        );
    }

    public function withCombatAttackFactory(CombatAttackFactory $combatAttackFactory)
    {
        $clone = clone $this;
        $clone->combatAttackFactory = $combatAttackFactory;
        return $clone;
    }
}
