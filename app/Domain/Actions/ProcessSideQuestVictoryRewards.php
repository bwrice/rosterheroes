<?php


namespace App\Domain\Actions;


use App\ChestBlueprint;
use App\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProcessSideQuestVictoryRewards
{
    /**
     * @var RewardChestToSquad
     */
    protected $rewardChestToSquad;

    public function __construct(RewardChestToSquad $rewardChestToSquad)
    {
        $this->rewardChestToSquad = $rewardChestToSquad;
    }

    /**
     * @param SideQuestResult $sideQuestResult
     * @throws \Throwable
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->rewards_processed_at) {
            throw new \Exception("Rewards already processed for SideQuestResult");
        }

        $sideQuestResult->rewards_processed_at = Date::now();
        $sideQuestResult->save();

        try {
            DB::transaction(function () use ($sideQuestResult) {

                $sideQuest = $sideQuestResult->sideQuest;
                $experienceReward = $sideQuest->getExperienceReward();

                $squad = $sideQuestResult->campaignStop->campaign->squad;
                $squad->getAggregate()->increaseExperience($experienceReward)->persist();

                $sideQuest->chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($squad) {
                    $this->rewardChestToSquad->execute($chestBlueprint, $squad);
                });
            });
        } catch (\Throwable $throwable) {

            $sideQuestResult->rewards_processed_at = null;
            $sideQuestResult->save();
            throw $throwable;
        }
    }
}
