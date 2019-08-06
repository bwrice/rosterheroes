<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ProvinceCollection;
use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Models\Province;
use Illuminate\Support\Facades\DB;

class FastTravelAction
{
    /**
     * @var BorderTravelAction
     */
    private $borderTravelAction;

    public function __construct(BorderTravelAction $borderTravelAction)
    {
        $this->borderTravelAction = $borderTravelAction;
    }

    /**
     * @param TravelsBorders $travelsBorders
     * @param ProvinceCollection $travelRoute
     */
    public function execute(TravelsBorders $travelsBorders, ProvinceCollection $travelRoute)
    {
        DB::transaction(function() use ($travelsBorders, $travelRoute) {

            $travelRoute->each(function(Province $border) use ($travelsBorders) {

                $travelsBorders = $travelsBorders->fresh();
                $this->borderTravelAction->execute($travelsBorders, $border);
            });
        });
    }
}
