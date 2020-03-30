<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\SideQuestEvent;
use App\SideQuestResult;

class ProcessSideQuestRewards
{
    /**
     * @var RewardSquadForMinionKill
     */
    protected $rewardSquadForMinionKill;
    /**
     * @var ProcessSideQuestVictoryRewards
     */
    protected $processSideQuestVictoryRewards;

    public function __construct(
        RewardSquadForMinionKill $rewardSquadForMinionKill,
        ProcessSideQuestVictoryRewards $processSideQuestVictoryRewards)
    {
        $this->rewardSquadForMinionKill = $rewardSquadForMinionKill;
        $this->processSideQuestVictoryRewards = $processSideQuestVictoryRewards;
    }

    public function execute(SideQuestResult $sideQuestResult)
    {
        $minionKillEvents = $sideQuestResult->sideQuestEvents()->heroKillsMinion()->get();
        $minionKillEvents->each(function (SideQuestEvent $sideQuestEvent) {
            $squad = $sideQuestEvent->getCombatHero()->getHero()->squad;
            $minion = $sideQuestEvent->getCombatMinion()->getMinion();
            $this->rewardSquadForMinionKill->execute($squad, $minion);
        });

        $victoryEvent = $sideQuestResult->sideQuestEvents()->victoryEvent();
        if ($victoryEvent) {
            $this->processSideQuestVictoryRewards->execute($sideQuestResult);
        }
    }
}
