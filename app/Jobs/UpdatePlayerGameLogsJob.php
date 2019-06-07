<?php

namespace App\Jobs;

use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Team;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UpdatePlayerGameLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const REDIS_THROTTLE_KEY = 'msf_update_player_game_logs';

    public $timeout = 60 * 5; // Allow 5 minutes before timing out

    public $retry_after = 60 * 6;

    public $tries = 5;


    /**
     * @var Team
     */
    private $team;
    /**
     * @var int
     */
    private $yearDelta;

    public function __construct(Team $team, int $yearDelta = 0)
    {
        if ( $yearDelta > 0 ) {
            throw new \RuntimeException("Year delta must be negative, " . $yearDelta . " was passed");
        }
        $this->team = $team;
        $this->yearDelta = $yearDelta;
    }

    /**
     * @param StatsIntegration $statsIntegration
     */
    public function handle(StatsIntegration $statsIntegration)
    {
        // Game log API has rate limit of 1 request per 10 seconds, we add another 5 seconds for buffer
        Redis::throttle(self::REDIS_THROTTLE_KEY)->allow(1)->every(15)->then(function () use ($statsIntegration) {
            // Job logic...
            $this->performJob($statsIntegration);
        }, function () {
            // Could not obtain lock...

            return $this->release(1);
        });
    }

    public function performJob(StatsIntegration $statsIntegration)
    {
        $start = microtime(true);
        $playerGameLogDTOs = $statsIntegration->getPlayerGameLogDTOs($this->team, $this->yearDelta);
        $end = microtime(true);
        Log::debug("Get player DTOs elapsed time: " . ($start - $end) . " seconds for team: " . $this->team->name);


        $start = microtime(true);
        $playerGameLogDTOs->each(function (PlayerGameLogDTO $dto) {

            $playerID = $dto->getPlayer()->id;
            $gameID = $dto->getGame()->id;
            $teamID = $dto->getTeam()->id;

            $playerGameLogs = PlayerGameLog::query()->where('player_id', '=', $playerID)
                ->where('game_id', '=', $gameID)
                ->where('team_id', '=', $teamID)->get();

            // TODO remove warning and allow for stat updates
            if ($playerGameLogs->isNotEmpty()) {
                Log::warning("Player, Game, Team combination already exists when attempting to create PlayerGameLog",
                    $playerGameLogs->loadMissing('playerStats')->toArray());
            } else {
                /** @var PlayerGameLog $playerGameLog */
                $playerGameLog = PlayerGameLog::query()->create([
                    'player_id' => $dto->getPlayer()->id,
                    'game_id' => $dto->getGame()->id,
                    'team_id' => $dto->getTeam()->id
                ]);

                $dto->getStatAmountDTOs()->each(function (StatAmountDTO $statAmountDTO) use ($playerGameLog) {
                    $playerGameLog->playerStats()->updateOrCreate([
                        'stat_type_id' => $statAmountDTO->getStatType()->id],
                        [
                            'amount' => $statAmountDTO->getAmount()
                        ]);
                });
            }
        });
        $end = microtime(true);
        Log::debug("Convert player DTOs elapsed time: " . ($start - $end) . " seconds for team: " . $this->team->name);
    }
}
