<?php

namespace App\Jobs;

use App\Domain\Actions\BuildPlayerGameLogsForGameAction;
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
     * @param BuildPlayerGameLogsForGameAction $domainAction
     */
    public function handle(BuildPlayerGameLogsForGameAction $domainAction)
    {
        $domainAction->execute($this->game, $this->yearDelta);
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
