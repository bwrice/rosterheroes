<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use Illuminate\Support\Collection;

class VerifyResourcesAvailable
{
    /**
     * @param Collection $resourceCosts
     * @param SpendsResources $spendsResources
     * @return bool
     */
    public function execute(Collection $resourceCosts, SpendsResources $spendsResources)
    {
        $firstFailure = $resourceCosts->first(function (ResourceCost $resourceCost) use ($spendsResources) {

            $staminaCost = $resourceCost->getStaminaCost($spendsResources);
            if ($staminaCost > $spendsResources->getCurrentStamina()) {
                return true;
            }

            $manaCost = $resourceCost->getManaCost($spendsResources);
            if ($manaCost > $spendsResources->getCurrentMana()) {
                return true;
            }

            return false;
        });

        return is_null($firstFailure);
    }
}
