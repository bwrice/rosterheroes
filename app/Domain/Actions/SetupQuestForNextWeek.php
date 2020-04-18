<?php


namespace App\Domain\Actions;


use App\Domain\Models\Quest;
use App\Facades\CurrentWeek;

class SetupQuestForNextWeek
{
    /**
     * @param Quest $quest
     * @throws \Exception
     */
    public function execute(Quest $quest)
    {
        if (! CurrentWeek::finalizing()) {
            throw new \Exception("Cannot move quest if week is not finalizing");
        }
        $newLocation = $quest->travelType->getBehavior()->getRandomProvinceToTravelTo($quest->province);
        $quest->province_id = $newLocation->id;
        $quest->save();
    }
}
