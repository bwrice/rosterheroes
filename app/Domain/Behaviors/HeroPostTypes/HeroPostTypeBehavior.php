<?php


namespace App\Domain\Behaviors\HeroPostTypes;


abstract class HeroPostTypeBehavior
{
    protected $baseCost = 250000;

    /**
     * @param int $overInitialOwnershipCount
     * @return int
     */
    public function getRecruitmentCost(int $overInitialOwnershipCount = 0)
    {
        switch ($overInitialOwnershipCount) {
            case 0:
                return $this->baseCost;
            case 1:
                return $this->baseCost * 4;
            case 2:
            default:
                return $this->baseCost * 4 * (5 ** $overInitialOwnershipCount - 1);
        }
    }

    public function getRecruitmentBonusSpiritEssence(int $overInitialOwnershipCount = 0)
    {
        switch ($overInitialOwnershipCount) {
            case 0:
                return 6000;
            case 1:
                return 5000;
            case 2:
            default:
                return 4000;
        }
    }
}
