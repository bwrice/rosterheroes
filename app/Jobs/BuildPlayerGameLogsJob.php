<?php

namespace App\Jobs;

use App\Domain\Collections\PositionCollection;
use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Team;
use App\External\Stats\StatsIntegration;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\Limiters\ConcurrencyLimiterBuilder;
use Illuminate\Redis\Limiters\DurationLimiterBuilder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Laravel\Telescope\Telescope;

class BuildPlayerGameLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const REDIS_THROTTLE_KEY = 'msf_update_player_game_logs';

    public $timeout = 60;

    public $retry_after = 30;

    public $tries = 3;


    /**
     * @var Game
     */
    private $game;
    /**
     * @var int
     */
    private $yearDelta;

    public function __construct(Game $game, int $yearDelta = 0)
    {
        if ( $yearDelta > 0 ) {
            throw new \RuntimeException("Year delta must be negative, " . $yearDelta . " was passed");
        }
        $this->game = $game;
        $this->yearDelta = $yearDelta;
    }

    /**
     * @param StatsIntegration $statsIntegration
     * @throws \Exception
     */
    public function handle(StatsIntegration $statsIntegration)
    {
        $this->performJob($statsIntegration);
    }

    public function performJob(StatsIntegration $statsIntegration)
    {
        $start = microtime(true);
        $playerGameLogDTOs = $statsIntegration->getPlayerGameLogDTOs($this->game, $this->yearDelta);
        $end = microtime(true);
        $diff = $end - $start;
        Log::debug("Get player DTOs elapsed time: " . $diff . " seconds for team: " . $this->game->name);

        $start = microtime(true);
        $playerGameLogDTOs->each(function (PlayerGameLogDTO $dto) {

            $playerID = $dto->getPlayer()->id;
            $gameID = $dto->getGame()->id;
            $teamID = $dto->getTeam()->id;

            /** @var PlayerGameLog $playerGameLog */
            $playerGameLog = PlayerGameLog::query()->firstOrCreate([
                'player_id' => $playerID,
                'game_id' => $gameID,
                'team_id' => $teamID
            ]);

            $dto->getStatAmountDTOs()->each(function (StatAmountDTO $statAmountDTO) use ($playerGameLog) {
                $playerGameLog->playerStats()->updateOrCreate([
                    'stat_type_id' => $statAmountDTO->getStatType()->id],
                    [
                        'amount' => $statAmountDTO->getAmount()
                    ]);
            });
        });
        $end = microtime(true);
        Log::debug("Convert player DTOs elapsed time: " . ($start - $end) . " seconds for team: " . $this->game->name);
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return int
     */
    public function getYearDelta(): int
    {
        return $this->yearDelta;
    }
}
