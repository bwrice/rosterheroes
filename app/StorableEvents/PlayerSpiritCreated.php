<?php

namespace App\StorableEvents;

use App\Domain\Models\PlayerSpirit;
use Spatie\EventProjector\ShouldBeStored;

final class PlayerSpiritCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $weekID;
    /**
     * @var int
     */
    public $playerID;
    /**
     * @var int
     */
    public $gameID;
    /**
     * @var int
     */
    public $essenceCost;
    /**
     * @var int
     */
    public $energy;
    /**
     * @var int
     */
    public $playerGameLogID;

    public function __construct(int $weekID, int $playerID, int $gameID, int $essenceCost, int $energy = PlayerSpirit::STARTING_ENERGY, int $playerGameLogID = null)
    {
        $this->weekID = $weekID;
        $this->playerID = $playerID;
        $this->gameID = $gameID;
        $this->essenceCost = $essenceCost;
        $this->energy = $energy;
        $this->playerGameLogID = $playerGameLogID;
    }
}
