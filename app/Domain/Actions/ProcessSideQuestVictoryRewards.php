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
                $squad = $sideQuestResult->campaignStop->campaign->squad;

                $experienceReward = $sideQuest->getExperienceReward();
                $favorReward = $sideQuest->getFavorReward();
                $squad->experience += $experienceReward;
                $squad->favor += $favorReward;
                $squad->save();

                $sideQuest->chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($squad, $sideQuestResult) {
                    $count = $chestBlueprint->pivot->count;
                    /*
                     * Chance is a float between 0 and 100 and possibly less than 1, and we want to use
                     * random int to determine if we should reward it, so we'll multiple by 100 so a less than 1%
                     * chance chest can actually be rewarded
                     */
                    $percentChanceTimes100 = $chestBlueprint->pivot->chance * 100;
                    for ($i = 1; $i <= $count; $i++) {

                        $rewardChest = (rand(1, 10000) <= $percentChanceTimes100);
                        if ($rewardChest) {
                            $this->rewardChestToSquad->execute($chestBlueprint, $squad, $sideQuestResult->sideQuest);
                        }
                    }
                });
            });
        } catch (\Throwable $throwable) {

            $sideQuestResult->rewards_processed_at = null;
            $sideQuestResult->save();
            throw $throwable;
        }
    }
}
