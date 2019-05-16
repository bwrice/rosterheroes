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

class UpdatePlayerGameLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Team
     */
    private $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @param StatsIntegration $statsIntegration
     */
    public function handle(StatsIntegration $statsIntegration)
    {
        $playerGameLogDTOs = $statsIntegration->getPlayerGameLogDTOs($this->team);

        $playerGameLogDTOs->each(function (PlayerGameLogDTO $dto) {

            $playerID = $dto->getPlayer()->id;
            $gameID = $dto->getGame()->id;
            $teamID = $dto->getTeam()->id;

            $playerGameLogs = PlayerGameLog::query()->where('player_id', '=', $playerID)
                ->where('game_id', '=', $gameID)
                ->where('team_id', '=', $teamID)->get();

            if ($playerGameLogs->isNotEmpty()) {
                Log::warning("Player, Game, Team combination already exists when attempting to create PlayerGameLog",
                    $playerGameLogs->loadMissing('stats')->toArray());
            } else {
                /** @var PlayerGameLog $playerGameLog */
                $playerGameLog = PlayerGameLog::query()->create([
                    'player_id' => $dto->getPlayer()->id,
                    'game_id' => $dto->getGame()->id,
                    'team_id' => $dto->getTeam()->id
                ]);

                $dto->getStatAmountDTOs()->each(function (StatAmountDTO $statAmountDTO) use ($playerGameLog) {
                    $playerGameLog->stats()->create([
                        'stat_type_id' => $statAmountDTO->getStatType()->id,
                        'amount' => $statAmountDTO->getAmount()
                    ]);
                });
            }
        });
    }
}
