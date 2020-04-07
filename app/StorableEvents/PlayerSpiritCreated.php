<?php

namespace App\StorableEvents;

use App\Domain\Models\PlayerSpirit;
use Spatie\EventSourcing\ShouldBeStored;

final class PlayerSpiritCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $weekID;
    /**
     * @var int
     */
    public $playerGameLogID;
    /**
     * @var int
     */
    public $essenceCost;
    /**
     * @var int
     */
    public $energy;

    public function __construct(int $weekID, int $playerGameLogID, int $essenceCost, int $energy = PlayerSpirit::STARTING_ENERGY)
    {
        $this->weekID = $weekID;
        $this->playerGameLogID = $playerGameLogID;
        $this->essenceCost = $essenceCost;
        $this->energy = $energy;
        $this->playerGameLogID = $playerGameLogID;
    }
}
