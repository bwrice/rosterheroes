<?php


namespace App\Domain\Actions;


use App\Domain\Models\Quest;
use App\Domain\Models\Squad;

class LeaveQuestAction extends SquadQuestAction
{
    public function execute(Squad $squad, Quest $quest)
    {
        $this->setProperties($squad, $quest);
        $this->validateWeek();
    }
}
