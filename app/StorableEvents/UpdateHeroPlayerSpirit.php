<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class UpdateHeroPlayerSpirit implements ShouldBeStored
{
    /**
     * @var int
     */
    public $playerSpiritID;

    public function __construct(int $playerSpiritID = null)
    {
        $this->playerSpiritID = $playerSpiritID;
    }
}
