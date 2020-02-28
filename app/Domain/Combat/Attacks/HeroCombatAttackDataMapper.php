<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\ResourceCostsCollection;

class HeroCombatAttackDataMapper
{
    /**
     * @var CombatAttackDataMapper
     */
    protected $combatAttackDataMapper;

    public function __construct(CombatAttackDataMapper $combatAttackDataMapper)
    {
        $this->combatAttackDataMapper = $combatAttackDataMapper;
    }

    public function getHeroCombatAttack(array $data)
    {
        return new HeroCombatAttack(
            $data['heroUuid'],
            $data['itemUuid'],
            $this->combatAttackDataMapper->getCombatAttack($data['combatAttack']),
            $this->getResourceCosts($data['resourceCosts'])
        );
    }

    protected function getResourceCosts(array $resourceCostsArray)
    {
        $resourcesCosts = new ResourceCostsCollection();
        return $resourcesCosts;
    }
}
