<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use Illuminate\Contracts\Support\Arrayable;

class MinionCombatAttack implements CombatAttackInterface, Arrayable
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

    /**
     * @return CombatAttack
     */
    public function getCombatAttack(): CombatAttack
    {
        return $this->combatAttack;
    }

    public function toArray()
    {
        return [
            'minionUuid' => $this->minionUuid,
            'combatAttack' => $this->combatAttack->toArray()
        ];
    }
}
