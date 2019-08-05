<?php


namespace App\Domain\Actions;


use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Services\Travel\BorderTravelCostCalculator;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\NotEnoughGoldException;

class BorderTravelAction
{
    /**
     * @var BorderTravelCostCalculator
     */
    private $costCalculator;

    public function __construct(BorderTravelCostCalculator $costCalculator)
    {
        $this->costCalculator = $costCalculator;
    }

    public function execute(TravelsBorders $travelsBorders, Province $border)
    {
        $currentLocation = $travelsBorders->getCurrentLocation();
        if(! $currentLocation->isBorderedBy($border)) {
            throw (new NotBorderedByException())->setProvinces($currentLocation, $border);
        }

        $availableGold = $travelsBorders->getAvailableGold();
        $costToTravel = $this->costCalculator->goldCost($travelsBorders, $border);

        if($availableGold < $costToTravel) {
            throw new NotEnoughGoldException($availableGold, $costToTravel);
        }
    }
}
