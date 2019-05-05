<?php

namespace App\Jobs;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class UpdateGamesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var League
     */
    private $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }


    /**
     * @param StatsIntegration $integration
     */
    public function handle(StatsIntegration $integration)
    {
        Log::notice("Beginning games update for League: " . $this->league->abbreviation);

        $gameDTOs = $integration->getGameDTOs($this->league);
        $gameDTOs->each(function (GameDTO $gameDTO) {
            Game::updateOrCreate([
                'external_id' => $gameDTO->getExternalID()
            ], [
                'starts_at' => $gameDTO->getStartsAt(),
                'home_team_id' => $gameDTO->getHomeTeam()->id,
                'away_team_id' => $gameDTO->getAwayTeam()->id,
            ]);
        });
    }
}
