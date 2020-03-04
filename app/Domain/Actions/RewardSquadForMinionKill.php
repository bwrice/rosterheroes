<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\Domain\Models\Squad;

class RewardSquadForMinionKill
{
    public function execute(Squad $squad, Minion $minion)
    {
        $experienceReward = $minion->getExperienceReward();
        $squad->getAggregate()->increaseExperience($experienceReward)->persist();

    }
}
