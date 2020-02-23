<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Attacks\CombatAttackInterface;

class HeroCombatAttack implements CombatAttackInterface
{
    /**
     * @var string
     */
    protected $heroUuid;
    /**
     * @var string
     */
    protected $itemUuid;
    /**
     * @var CombatAttack
     */
    protected $combatAttack;

    public function __construct(
        string $heroUuid,
        string $itemUuid,
        CombatAttack $combatAttack,
        ResourceCostsCollection $resourceCosts)
    {
        $this->heroUuid = $heroUuid;
        $this->itemUuid = $itemUuid;
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
    public function getHeroUuid()
    {
        return $this->heroUuid;
    }

    /**
     * @return string
     */
    public function getItemUuid()
    {
        return $this->itemUuid;
    }

    /**
     * @return CombatAttack
     */
    public function getCombatAttack(): CombatAttack
    {
        return $this->combatAttack;
    }
}
