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
    /**
     * @var string|null
     */
    public $description;
    /**
     * @var string|null
     */
    public $sourceType;
    /**
     * @var int|null
     */
    public $sourceID;

    public function __construct(
        int $quality,
        int $size,
        int $gold,
        int $squadID,
        ?int $chestBlueprintID,
        ?string $description,
        string $sourceType = null,
        int $sourceID = null)
    {
        $this->quality = $quality;
        $this->size = $size;
        $this->gold = $gold;
        $this->squadID = $squadID;
        $this->chestBlueprintID = $chestBlueprintID;
        $this->description = $description;
        $this->sourceType = $sourceType;
        $this->sourceID = $sourceID;
    }
}
