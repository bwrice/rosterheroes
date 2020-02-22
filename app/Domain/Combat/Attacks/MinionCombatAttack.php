<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;

class MinionCombatAttack implements CombatAttackInterface
{
    /**
     * @var int
     */
    protected $minionID;
    /**
     * @var CombatAttack
     */
    protected $combatAttack;

    public function __construct(int $minionID, CombatAttack $combatAttack)
    {
        $this->minionID = $minionID;
        $this->combatAttack = $combatAttack;
    }

    public function getDamagePerTarget(int $targetsCount): int
    {
        return $this->combatAttack->getDamagePerTarget($targetsCount);
    }

    public function getTargets(CombatantCollection $possibleTargets): CombatantCollection
    {
        return $this->combatAttack->getTargets($possibleTargets);
    }

    /**
     * @return int
     */
    public function getMinionID(): int
    {
        return $this->minionID;
    }
}
