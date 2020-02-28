<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;

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
        $resourcesCosts = collect($resourceCostsArray)->map(function ($resourceCostArray) {
            if ($resourceCostArray['type'] === 'fixed') {
                return new FixedResourceCost($resourceCostArray['resource'], $resourceCostArray['amount']);
            }
            return new PercentResourceCost($resourceCostArray['resource'], $resourceCostArray['percent']);
        });
        return new ResourceCostsCollection($resourcesCosts);
    }
}
