<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($sideQuestResult) {
            $squad = $sideQuestResult->campaignStop->campaign->squad;

            $finalEvent = $sideQuestResult->sideQuestEvents()->finalEvent();
            if ($finalEvent) {
                $experienceForMoments = (int) ceil($finalEvent->moment * $sideQuestResult->sideQuest->getExperiencePerMoment());
                $squadAggregate = $squad->getAggregate();
                $squadAggregate->increaseExperience($experienceForMoments)->persist();
            }

            $minionKillEvents = $sideQuestResult->sideQuestEvents()->heroKillsMinion()->get();
            $minionKillEvents->each(function (SideQuestEvent $sideQuestEvent) use ($squad) {
                $minion = $sideQuestEvent->getCombatMinion()->getMinion();
                $this->rewardSquadForMinionKill->execute($squad->fresh(), $minion);
            });

            $victoryEvent = $sideQuestResult->sideQuestEvents()->victoryEvent();
            if ($victoryEvent) {
                $this->processSideQuestVictoryRewards->execute($sideQuestResult);
            }

            $sideQuestResult->rewards_processed_at = Date::now();
            $sideQuestResult->save();
        });
    }
}
