<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;

class MinionCombatAttack implements CombatAttackInterface
{
    /**
     * @var string
     */
    protected $minionUuid;
    /**
     * @var CombatAttack
     */
    protected $combatAttack;

    public function __construct(string $minionUuid, CombatAttack $combatAttack)
    {
        $this->minionUuid = $minionUuid;
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
     * @return string
     */
    public function getMinionUuid(): string
    {
        return $this->minionUuid;
    }
}
