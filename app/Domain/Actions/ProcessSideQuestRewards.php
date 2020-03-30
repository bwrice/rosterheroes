<?php


namespace App\Domain\Actions;


use App\SideQuestResult;

class ProcessSideQuestRewards
{
    /**
     * @var ProcessSideQuestVictoryRewards
     */
    private $processSideQuestVictoryRewards;

    public function __construct(ProcessSideQuestVictoryRewards $processSideQuestVictoryRewards)
    {
        $this->processSideQuestVictoryRewards = $processSideQuestVictoryRewards;
    }

    public function execute(SideQuestResult $sideQuestResult)
    {
        $victoryEvent = $sideQuestResult->sideQuestEvents()->victoryEvent();
        if ($victoryEvent) {
            $this->processSideQuestVictoryRewards->execute($sideQuestResult);
        }
    }
}
