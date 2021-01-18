<?php


namespace App\Domain\Actions;

use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;
use App\Exceptions\SquadTravelException;
use App\Jobs\CreateSquadEntersProvinceEventJob;
use App\Jobs\CreateSquadLeavesProvinceEventJob;
use Illuminate\Support\Facades\DB;

class SquadBorderTravelAction
{
    protected SquadBorderTravelCostCalculator $costCalculator;

    public function __construct(SquadBorderTravelCostCalculator $costCalculator)
    {
        $this->costCalculator = $costCalculator;
    }

    /**
     * @param Squad $squad
     * @param Province $border
     * @throws SquadTravelException
     */
    public function execute(Squad $squad, Province $border)
    {
        $currentLocation = $squad->province;
        $this->validateBorder($squad, $currentLocation, $border);
        $this->validateLevelRequirement($squad, $border);

        $availableGold = $squad->getAvailableGold();
        $costToTravel = $this->costCalculator->calculateGoldCost($squad, $border);

        $this->validateTravelCost($squad, $border, $availableGold, $costToTravel);

        DB::transaction(function() use ($squad, $border, $costToTravel) {
            if ($costToTravel > 0) {
                $squad->decreaseGold($costToTravel);
            }
            $squad->province_id = $border->id;
            $squad->save();
        });

        $now = now();
        CreateSquadLeavesProvinceEventJob::dispatch($currentLocation, $border, $squad, $now);
        CreateSquadEntersProvinceEventJob::dispatch($border, $currentLocation, $squad, $now, $costToTravel);
    }

    /**
     * @param Squad $squad
     * @param Province $currentLocation
     * @param Province $border
     * @throws SquadTravelException
     */
    protected function validateBorder(Squad $squad, Province $currentLocation, Province $border): void
    {
        if (!$currentLocation->isBorderedBy($border)) {
            $message = $currentLocation->name . ' is not bordered by ' . $border->name;
            throw new SquadTravelException($squad, $border, $message, SquadTravelException::NOT_BORDERED_BY);
        }
    }

    /**
     * @param Squad $squad
     * @param Province $border
     * @param int $availableGold
     * @param int $costToTravel
     * @throws SquadTravelException
     */
    protected function validateTravelCost(Squad $squad, Province $border, int $availableGold, int $costToTravel): void
    {
        if ($availableGold < $costToTravel) {
            $message = $costToTravel . " gold is needed, but only " . $availableGold . " gold available";
            throw new SquadTravelException($squad, $border, $message, SquadTravelException::NOT_ENOUGH_GOLD);
        }
    }

    /**
     * @param Squad $squad
     * @param Province $border
     * @throws SquadTravelException
     */
    protected function validateLevelRequirement(Squad $squad, Province $border)
    {
        $continent = $border->continent;
        $minLevelRequirement = $continent->getBehavior()->getMinLevelRequirement();
        $squadLevel = $squad->level();
        if ($squadLevel < $minLevelRequirement) {
            $exceptionMessage = $squad->name . " has a level of " . $squadLevel . ", but level ";
            $exceptionMessage .= $minLevelRequirement . " required to enter the continent of " . $continent->name;
            throw new SquadTravelException($squad, $border, $exceptionMessage, SquadTravelException::MIN_LEVEL_NOT_MET);
        }
    }
}
