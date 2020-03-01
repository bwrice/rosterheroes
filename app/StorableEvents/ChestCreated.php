<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class ChestCreated implements ShouldBeStored
{
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

    public function __construct(int $grade, int $gold, int $squadID,  int $chestBlueprintID = null)
    {
        $this->grade = $grade;
        $this->gold = $gold;
        $this->squadID = $squadID;
        $this->chestBlueprintID = $chestBlueprintID;
    }
}
