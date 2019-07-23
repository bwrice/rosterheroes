<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class PlayerSpiritAddedToHero implements ShouldBeStored
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
