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

    /**
     * @param SideQuestResult $sideQuestResult
     * @throws \Exception
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->rewards_processed_at) {
            throw new \Exception("Rewards already processed for SideQuestResult");
        }

        DB::transaction(function () use ($sideQuestResult) {
            $squad = $sideQuestResult->campaignStop->campaign->squad;

            $experienceEarned = 0;
            $favorEarned = 0;
            $finalEvent = $sideQuestResult->sideQuestEvents()->finalEvent();

            if ($finalEvent) {
                $experienceForMoments = (int) ceil($finalEvent->moment * $sideQuestResult->sideQuest->getExperiencePerMoment());
                $squad->experience += $experienceForMoments;
                $squad->save();

                $experienceEarned += $experienceForMoments;
            }

            $minionKillEvents = $sideQuestResult->sideQuestEvents()->heroKillsMinion()->get();

            $minionKillEvents->each(function (SideQuestEvent $sideQuestEvent) use ($squad, &$experienceEarned, &$favorEarned) {
                $minion = $sideQuestEvent->getCombatMinion()->getMinion();
                $earnings = $this->rewardSquadForMinionKill->execute($squad->fresh(), $minion);
                $experienceEarned += $earnings['experience'];
                $favorEarned += $earnings['favor'];
            });

            $victoryEvent = $sideQuestResult->sideQuestEvents()->victoryEvent();
            if ($victoryEvent) {
                $earnings = $this->processSideQuestVictoryRewards->execute($sideQuestResult);
                $experienceEarned += $earnings['experience'];
                $favorEarned += $earnings['favor'];
            }

            $sideQuestResult->rewards_processed_at = Date::now();
            $sideQuestResult->experience_rewarded = $experienceEarned;
            $sideQuestResult->favor_rewarded = $favorEarned;
            $sideQuestResult->save();
        });
    }
}
