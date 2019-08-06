<?php


namespace App\Domain\Actions;


use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Models\Province;
use App\Domain\Services\Travel\BorderTravelCostCalculator;
use App\Exceptions\BorderTravelException;

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

    /**
     * @param TravelsBorders $travelsBorders
     * @param Province $border
     * @throws BorderTravelException
     */
    public function execute(TravelsBorders $travelsBorders, Province $border)
    {
        $this->validateBorder($travelsBorders, $border);

        $availableGold = $travelsBorders->getAvailableGold();
        $costToTravel = $this->costCalculator->goldCost($travelsBorders, $border);

        $this->validateTravelCost($travelsBorders, $border, $availableGold, $costToTravel);

        $travelsBorders->decreaseGold($costToTravel);
        $travelsBorders->updateLocation($border);
    }

    /**
     * @param TravelsBorders $travelsBorders
     * @param Province $border
     * @throws BorderTravelException
     */
    protected function validateBorder(TravelsBorders $travelsBorders, Province $border): void
    {
        $currentLocation = $travelsBorders->getCurrentLocation();
        if (!$currentLocation->isBorderedBy($border)) {
            $message = $currentLocation->name . ' is not bordered by ' . $border->name;
            throw new BorderTravelException($travelsBorders, $border, $message, BorderTravelException::NOT_BORDERED_BY);
        }
    }

    /**
     * @param TravelsBorders $travelsBorders
     * @param Province $border
     * @param int $availableGold
     * @param int $costToTravel
     * @throws BorderTravelException
     */
    protected function validateTravelCost(TravelsBorders $travelsBorders, Province $border, int $availableGold, int $costToTravel): void
    {
        if ($availableGold < $costToTravel) {
            $message = $costToTravel . " gold is needed, but only " . $availableGold . " gold available";
            throw new BorderTravelException($travelsBorders, $border, $message, BorderTravelException::NOT_ENOUGH_GOLD);
        }
    }
}
