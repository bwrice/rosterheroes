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
        $squad->getAggregate()->increaseExperience($experienceReward)->persist();

        $minion->chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($squad) {
            $count = $chestBlueprint->pivot->count;
            for ($i = 1; $i <= $count; $i++) {
                $this->rewardChestToSquad->execute($chestBlueprint, $squad);
            }
        });
    }
}
