<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class ChestCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $qualityTier;
    /**
     * @var int
     */
    public $sizeTier;
    /**
     * @var int
     */
    public $grade;
    /**
     * @var int
     */
    public $gold;
    /**
     * @var int
     */
    public $squadID;
    /**
     * @var int|null
     */
    public $chestBlueprintID;

    public function __construct(int $qualityTier, int $sizeTier, int $gold, int $squadID,  int $chestBlueprintID = null)
    {
        $this->qualityTier = $qualityTier;
        $this->sizeTier = $sizeTier;
        $this->gold = $gold;
        $this->squadID = $squadID;
        $this->chestBlueprintID = $chestBlueprintID;
    }
}
