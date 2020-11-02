<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProcessSideQuestRewards
{
    public const EXCEPTION_CODE_COMBAT_NOT_PROCESSED = 1;
    public const EXCEPTION_CODE_REWARDS_ALREADY_PROCESSED = 2;

    protected RewardSquadForMinionKill $rewardSquadForMinionKill;
    protected ProcessSideQuestVictoryRewards $processSideQuestVictoryRewards;

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
        if (! $sideQuestResult->combat_processed_at) {
            throw new \Exception("Combat not yet processed for SideQuestResult", self::EXCEPTION_CODE_COMBAT_NOT_PROCESSED);
        }
        if ($sideQuestResult->rewards_processed_at) {
            throw new \Exception("Rewards already processed for SideQuestResult", self::EXCEPTION_CODE_REWARDS_ALREADY_PROCESSED);
        }

        DB::transaction(function () use ($sideQuestResult) {
            $squad = $sideQuestResult->campaignStop->campaign->squad;

            $experienceEarned = 0;
            $favorEarned = 0;
            $finalEvent = $sideQuestResult->sideQuestEvents()->finalEvent();

            if ($finalEvent) {
                $experienceForMoments = (int) ceil($finalEvent->moment * $sideQuestResult->sideQuestSnapshot->experience_per_moment);
                $squad->experience += $experienceForMoments;
                $squad->save();

                $experienceEarned += $experienceForMoments;
            }

            $minionKillEvents = $sideQuestResult->sideQuestEvents()->heroKillsMinion()->get();

            $minionKillEvents->each(function (SideQuestEvent $sideQuestEvent) use ($squad, &$experienceEarned, &$favorEarned) {
                $minionSnapshotUuid = $sideQuestEvent->data['minion']['sourceUuid'];
                $minionSnapshot = MinionSnapshot::findUuidOrFail($minionSnapshotUuid);
                $earnings = $this->rewardSquadForMinionKill->execute($squad->fresh(), $minionSnapshot);
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
