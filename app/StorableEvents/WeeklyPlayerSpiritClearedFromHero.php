<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class WeeklyPlayerSpiritClearedFromHero implements ShouldBeStored
{
    /**
     * @var int
     */
    public $playerSpiritID;

    public function __construct(int $playerSpiritID)
    {
        $this->playerSpiritID = $playerSpiritID;
    }
}
