<?php


namespace App\External\Stats\FakeStats;


use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Team;

class AddFakeStatsToPlayerGameLogDTO
{
    protected $absoluteMinGamesCount = 5;

    public function execute(PlayerGameLogDTO $playerGameLogDTO): PlayerGameLogDTO
    {
        $position = $playerGameLogDTO->getPlayer()->positions->first();
        if (!$position) {
            return $playerGameLogDTO;
        }

        $gamesNeededCount = ceil(($position->getBehavior()->getGamesPerSeason()/10) + $this->absoluteMinGamesCount);
    }

    /**
     * @param int $absoluteMinGamesCount
     * @return AddFakeStatsToPlayerGameLogDTO
     */
    public function setAbsoluteMinGamesCount(int $absoluteMinGamesCount): AddFakeStatsToPlayerGameLogDTO
    {
        $this->absoluteMinGamesCount = $absoluteMinGamesCount;
        return $this;
    }
}
