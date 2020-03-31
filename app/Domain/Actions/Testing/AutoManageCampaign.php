<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\JoinQuestAction;
use App\Domain\Actions\SquadBorderTravelAction;
use App\Domain\Models\Squad;

class AutoManageCampaign
{
    public function __construct(SquadBorderTravelAction $borderTravelAction, JoinQuestAction $joinQuestAction)
    {
    }

    public function execute(Squad $squad)
    {
        foreach (range (1, $squad->getQuestsPerWeek()) as $count) {
            if ($count > 1) {
                $squad = $squad->fresh();
            }
        }
    }
}
