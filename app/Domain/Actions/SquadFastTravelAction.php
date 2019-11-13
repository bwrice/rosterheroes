<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Illuminate\Support\Facades\DB;

class SquadFastTravelAction
{
    /**
     * @var SquadBorderTravelAction
     */
    private $borderTravelAction;

    public function __construct(SquadBorderTravelAction $borderTravelAction)
    {
        $this->borderTravelAction = $borderTravelAction;
    }

    /**
     * @param Squad $squad
     * @param ProvinceCollection $travelRoute
     */
    public function execute(Squad $squad, ProvinceCollection $travelRoute)
    {
        DB::transaction(function() use ($squad, $travelRoute) {

            $travelRoute->each(function(Province $border) use ($squad) {

                $travelsBorders = $squad->fresh();
                $this->borderTravelAction->execute($travelsBorders, $border);
            });
        });
    }
}
