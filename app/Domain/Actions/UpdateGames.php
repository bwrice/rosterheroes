<?php


namespace App\Domain\Actions;


use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\League;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdateSingleGameJob;

class UpdateGames
{
    /**
     * @var StatsIntegration
     */
    protected $statsIntegration;

    public function __construct(StatsIntegration $statsIntegration)
    {
        $this->statsIntegration = $statsIntegration;
    }

    public function execute(League $league, int $yearDelta = 0, $regularSeason = true)
    {
        if ( $yearDelta > 0 ) {
            throw new \RuntimeException("Year delta must be negative, " . $yearDelta . " was passed");
        }
        $gameDTOs = $this->statsIntegration->getGameDTOs($league, $yearDelta, $regularSeason);
        $gameDTOs->each(function (GameDTO $gameDTO) {
            UpdateSingleGameJob::dispatch($gameDTO);
        });
    }
}
