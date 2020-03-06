<?php


namespace App\Domain\Actions;


use App\ChestBlueprint;
use App\SideQuestResult;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProcessSideQuestRewards
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
     * @throws \Exception
     */
    public function execute(SideQuestResult $sideQuestResult)
    {
        if ($sideQuestResult->rewards_processed_at) {
            throw new \Exception("Rewards already processed for SideQuestResult");
        }

        DB::transaction(function () use ($sideQuestResult) {

            $sideQuest = $sideQuestResult->sideQuest;
            $experienceReward = $sideQuest->getExperienceReward();

            $squad = $sideQuestResult->campaign->squad;
            $squad->getAggregate()->increaseExperience($experienceReward)->persist();

            $sideQuest->chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($squad) {
                $this->rewardChestToSquad->execute($chestBlueprint, $squad);
            });

            $sideQuestResult->rewards_processed_at = Date::now();
            $sideQuestResult->save();
        });
    }
}
