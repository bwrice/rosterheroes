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
use Illuminate\Support\Facades\Redis;

class UpdateGamesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const REDIS_THROTTLE_KEY = 'msf_update_games';

    /**
     * @var League
     */
    private $league;
    /**
     * @var int
     */
    private $yearDelta;

    public function __construct(League $league, int $yearDelta = 0)
    {
        if ( $yearDelta > 0 ) {
            throw new \RuntimeException("Year delta must be negative, " . $yearDelta . " was passed");
        }
        $this->league = $league;
        $this->yearDelta = $yearDelta;
    }

    public function handle(StatsIntegration $statsIntegration)
    {

        // Game log API has rate limit of 1 request per 10 seconds, we add another 5 seconds for buffer
        Redis::throttle(self::REDIS_THROTTLE_KEY)->allow(10)->every(60)->then(function () use ($statsIntegration) {
            // Job logic...
            $this->performJob($statsIntegration);
        }, function () {
            // Could not obtain lock...

            return $this->release(10);
        });
    }


    /**
     * @param StatsIntegration $integration
     */
    public function performJob(StatsIntegration $integration)
    {
        Log::notice("Beginning games update for League: " . $this->league->abbreviation);

        $gameDTOs = $integration->getGameDTOs($this->league, $this->yearDelta);
        $gameDTOs->each(function (GameDTO $gameDTO) {
            Game::query()->updateOrCreate([
                'external_id' => $gameDTO->getExternalID()
            ], [
                'starts_at' => $gameDTO->getStartsAt(),
                'home_team_id' => $gameDTO->getHomeTeam()->id,
                'away_team_id' => $gameDTO->getAwayTeam()->id,
            ]);
        });
    }
}
