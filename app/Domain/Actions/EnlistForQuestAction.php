<?php


namespace App\Domain\Actions;


use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;

class EnlistForQuestAction
{
    public function execute(Squad $squad, Quest $quest)
    {
        $week = Week::current();
        if (! $week->adventuringOpen()) {
            throw (new CampaignException("Week is currently locked", CampaignException::CODE_WEEK_LOCKED))->setSquad($squad);
        }
    }
}
