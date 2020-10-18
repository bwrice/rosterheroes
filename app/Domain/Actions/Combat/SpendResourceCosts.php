<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;

class SpendResourceCosts
{
    /**
     * @param SpendsResources $spendsResources
     * @param ResourceCost $resourceCost
     * @return array
     */
    public function execute(SpendsResources $spendsResources, ResourceCost $resourceCost)
    {
        $staminaCost = $resourceCost->getStaminaCost($spendsResources);
        $currentStamina = max(0, $spendsResources->getCurrentStamina() - $staminaCost);
        $spendsResources->setCurrentStamina($currentStamina);
        $manaCost = $resourceCost->getManaCost($spendsResources);
        $currentMana = max(0, $spendsResources->getCurrentMana() - $manaCost);
        $spendsResources->setCurrentMana($currentMana);

        return [
            'stamina_cost' => $staminaCost,
            'mana_cost' => $manaCost
        ];
    }
}
