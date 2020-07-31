<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\Position;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

// TODO: Delete?
class DisableInsignificantPlayerSpirit
{
    /**
     * @var DisablePlayerSpirit
     */
    private $disablePlayerSpirit;

    public function __construct(DisablePlayerSpirit $disablePlayerSpirit)
    {
        $this->disablePlayerSpirit = $disablePlayerSpirit;
    }

    public function execute(PlayerSpirit $playerSpirit)
    {
        $player = $playerSpirit->playerGameLog->player;
        /** @var Position $position */
        $position = $player->positions->first();
        $position->sport->getBehavior();
        $significantAmountOfGamesWithoutStats = $position->getBehavior()->getSignificantAmountOfGamesWithoutStats();
        $gamesToConsiderCount = $significantAmountOfGamesWithoutStats * 3;

        $gameLogs = $this->getGameLogsToConsider($player, $gamesToConsiderCount);

        // Won't disable rookies
        if ($gameLogs->isEmpty()) {
            return false;
        }

        if ($this->disableBasedOnGameLogsWithoutStats($playerSpirit, $gameLogs->take($significantAmountOfGamesWithoutStats))) {
            return true;
        }

        return $this->disableBasedOnInsignificantStats($playerSpirit, $gameLogs);
    }

    /**
     * @param Player $player
     * @param int $gamesToConsiderCount
     * @return Collection
     */
    protected function getGameLogsToConsider(Player $player, int $gamesToConsiderCount): Collection
    {
        $gameIDs = Game::query()
            ->orderByDesc('starts_at')
            ->where('starts_at', '<', Date::now()->subHours(6)) // Don't want to include a game potentially still running
            ->whereHas('playerGameLogs', function (Builder $builder) use ($player) {
                return $builder->where('player_id', '=', $player->id);
            })->take($gamesToConsiderCount)->pluck('id')->toArray();

        $gameLogs = $player->playerGameLogs()
            ->with([
                'playerStats.statType',
                'game'
            ])
            ->whereIn('game_id', $gameIDs)
            ->get()->sortByDesc(function (PlayerGameLog $playerGameLog) {
                return $playerGameLog->game->starts_at->timestamp;
            });
        return $gameLogs;
    }

    /**
     * @param PlayerSpirit $playerSpirit
     * @param Collection $logsRequiredToHaveStats
     * @return bool
     */
    protected function disableBasedOnGameLogsWithoutStats(PlayerSpirit $playerSpirit, Collection $logsRequiredToHaveStats): bool
    {
        $hasStatsGameLog = $logsRequiredToHaveStats->first(function (PlayerGameLog $playerGameLog) {
            return $playerGameLog->playerStats->isNotEmpty();
        });

        if (is_null($hasStatsGameLog)) {
            $this->disablePlayerSpirit->execute($playerSpirit);
            return true;
        }

        return false;
    }

    /**
     * @param PlayerSpirit $playerSpirit
     * @param Collection $gameLogs
     * @return bool
     */
    protected function disableBasedOnInsignificantStats(PlayerSpirit $playerSpirit, Collection $gameLogs): bool
    {
        $insignificantGameLogs = $gameLogs->filter(function (PlayerGameLog $playerGameLog) {
            $totalPointsSum = $playerGameLog->playerStats->sum(function (PlayerStat $playerStat) {
                return $playerStat->statType->getBehavior()->getTotalPoints($playerStat->amount);
            });

            return $totalPointsSum < 2.5;
        });

        if (($insignificantGameLogs->count() / $gameLogs->count()) > .75) {
            $this->disablePlayerSpirit->execute($playerSpirit);
            return true;
        }
        return false;
    }
}
