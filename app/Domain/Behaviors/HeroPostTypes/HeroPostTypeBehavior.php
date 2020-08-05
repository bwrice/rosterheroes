<?php


namespace App\Domain\Behaviors\HeroPostTypes;


abstract class HeroPostTypeBehavior
{
    protected $baseCost = 250000;

    public function getRecruitmentCost(int $overInitialOwnershipCount = 1)
    {
        switch ($overInitialOwnershipCount) {
            case 1:
                return $this->baseCost;
            case 2:
                return $this->baseCost * 4;
            case 3:
            default:
                return $this->baseCost * 4 * (5 ** $overInitialOwnershipCount - 1);
        }
    }
}
