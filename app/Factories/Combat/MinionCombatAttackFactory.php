<?php


namespace App\Factories\Combat;

use App\Domain\Combat\Attacks\MinionCombatAttack;
use Illuminate\Support\Str;

class MinionCombatAttackFactory
{
    protected $combatAttackFactory;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $minionUuid = (string) Str::uuid();
        $combatAttackFactory = $this->combatAttackFactory ?: CombatAttackFactory::new();
        $combatAttack = $combatAttackFactory->create();
        return new MinionCombatAttack(
            $minionUuid,
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
