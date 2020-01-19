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
        $gameDTOs = $statsIntegration->getGameDTOs($this->league, $this->yearDelta);
        $integrationType = $statsIntegration->getIntegrationType();
        $count = 0;
        $gameDTOs->each(function (GameDTO $gameDTO) use ($integrationType, &$count) {

            $game = Game::query()->forIntegration($integrationType->id, $gameDTO->getExternalID())->first();

            if ($game) {
                $this->updateGame($game, $gameDTO);

            } else {
                /** @var Game $game */
                $game = Game::query()->create([
                    'starts_at' => $gameDTO->getStartsAt(),
                    'home_team_id' => $gameDTO->getHomeTeam()->id,
                    'away_team_id' => $gameDTO->getAwayTeam()->id,
                ]);

                $game->externalGames()->create([
                    'integration_type_id' => $integrationType->id,
                    'external_id' => $gameDTO->getExternalID()
                ]);

                $count++;
            }
        });
        if ($count > 0) {
            Log::alert($count . " new games created for league: " . $this->league->abbreviation);
        }
    }

    protected function updateGame(Game $game, GameDTO $gameDTO)
    {
        if ($game->starts_at->timestamp !== $gameDTO->getStartsAt()->timestamp) {
            $game->starts_at = $gameDTO->getStartsAt();
            $game->save();
        }
        return $game;
    }
}
