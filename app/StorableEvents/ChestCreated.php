<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class ChestCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $quality;
    /**
     * @var int
     */
    public $size;
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

    public function __construct(int $quality, int $size, int $gold, int $squadID, int $chestBlueprintID = null)
    {
        $this->quality = $quality;
        $this->size = $size;
        $this->gold = $gold;
        $this->squadID = $squadID;
        $this->chestBlueprintID = $chestBlueprintID;
    }
}
