<?php


namespace App\Domain\Actions;


use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;

class BuildFastTravelRoute
{
    /**
     * @var FindPathBetweenProvinces
     */
    protected FindPathBetweenProvinces $findPathBetweenProvinces;
    /**
     * @var SquadBorderTravelCostCalculator
     */
    protected SquadBorderTravelCostCalculator $costCalculator;

    public function __construct(
        FindPathBetweenProvinces $findPathBetweenProvinces,
        SquadBorderTravelCostCalculator $costCalculator)
    {
        $this->findPathBetweenProvinces = $findPathBetweenProvinces;
        $this->costCalculator = $costCalculator;
    }

    public function execute(Squad $squad, Province $destination)
    {
        $provinces = $this->findPathBetweenProvinces->execute($squad->province, $destination)->toBase();
        return $provinces->map(function (Province $province) use ($squad) {
           return [
               'province' => $province,
               'cost' => $this->costCalculator->calculateGoldCost($squad, $province)
           ];
        });
    }
}
