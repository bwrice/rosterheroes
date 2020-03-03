<?php


namespace App\Domain\Combat\Attacks;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\AbstractCombatantDataMapper;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\TargetPriority;
use App\Factories\Combat\AbstractCombatAttackFactory;
use Illuminate\Database\Eloquent\Collection;

class HeroCombatAttackDataMapper extends AbstractCombatAttackDataMapper
{

    public function getHeroCombatAttack(array $data, Collection $combatPositions = null, Collection $targetPriorities = null, Collection $damageTypes = null)
    {
        $combatPositions = $combatPositions ?: CombatPosition::all();
        $targetPriorities = $targetPriorities ?: TargetPriority::all();
        $damageTypes = $damageTypes ?: DamageType::all();

        return new HeroCombatAttack(
            $data['heroUuid'],
            $data['itemUuid'],
            $this->getName($data),
            $this->getAttackUuid($data),
            $this->getDamage($data),
            $this->getCombatSpeed($data),
            $this->getGrade($data),
            $this->getMaxTargetsCount($data),
            $this->getAttackerPosition($data, $combatPositions),
            $this->getTargetPosition($data, $combatPositions),
            $this->getTargetPriority($data, $targetPriorities),
            $this->getDamageTypes($data, $damageTypes),
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
