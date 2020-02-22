<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\Minion;

class BuildMinionCombatAttack
{
    /**
     * @var BuildCombatAttack
     */
    protected $buildCombatAttack;

    public function __construct(BuildCombatAttack $buildCombatAttack)
    {
        $this->buildCombatAttack = $buildCombatAttack;
    }

    /**
     * @param Attack $attack
     * @param Minion $minion
     * @return MinionCombatAttack
     */
    public function execute(Attack $attack, Minion $minion)
    {
        return new MinionCombatAttack($minion->id, $this->buildCombatAttack->execute($attack, $minion));
    }
}
