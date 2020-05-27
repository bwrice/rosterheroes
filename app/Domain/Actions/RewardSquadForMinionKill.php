<?php


namespace App\Domain\Actions;


use App\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\Squad;

class RewardSquadForMinionKill
{
    /**
     * @var RewardChestToSquad
     */
    protected $rewardChestToSquad;

    public function __construct(RewardChestToSquad $rewardChestToSquad)
    {
        $this->rewardChestToSquad = $rewardChestToSquad;
    }

    public function execute(Squad $squad, Minion $minion)
    {
        $experienceReward = $minion->getExperienceReward();
        $favorReward = $minion->getFavorReward();
        $squad->experience += $experienceReward;
        $squad->favor += $favorReward;
        $squad->save();

        $minion->chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($squad, $minion) {

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
                    $this->rewardChestToSquad->execute($chestBlueprint, $squad, $minion);
                }
            }
        });

        return [
            'experience' => $experienceReward,
            'favor' => $favorReward
        ];
    }
}
