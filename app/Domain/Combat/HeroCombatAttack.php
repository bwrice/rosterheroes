<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatantCollection;

class HeroCombatAttack implements CombatAttackInterface
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
     * @var CombatAttack
     */
    protected $combatAttack;

    public function __construct(
        int $heroID,
        int $itemID,
        CombatAttack $combatAttack)
    {
        $this->heroID = $heroID;
        $this->itemID = $itemID;
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
    public function getHeroID(): int
    {
        return $this->heroID;
    }

    /**
     * @return int
     */
    public function getItemID(): int
    {
        return $this->itemID;
    }

    /**
     * @return CombatAttack
     */
    public function getCombatAttack(): CombatAttack
    {
        return $this->combatAttack;
    }
}
