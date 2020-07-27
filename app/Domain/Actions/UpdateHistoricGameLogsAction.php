<?php


namespace App\Domain\Actions;


use App\Domain\Collections\GameCollection;
use App\Domain\Collections\LeagueCollection;
use App\Domain\Models\Game;
use App\Domain\QueryBuilders\GameQueryBuilder;
use App\Jobs\UpdatePlayerGameLogsJob;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class UpdateHistoricGameLogsAction
{
    /**
     * @param LeagueCollection|null $leagues
     * @param bool $force
     * @param int $yearDelta
     * @return int
     */
    public function execute(LeagueCollection $leagues = null, $force = false, int $yearDelta = 0): int
    {
        $count = 0;
        $this->getGameQuery($leagues, $force)->chunk(100, function (GameCollection $games) use (&$count, $yearDelta) {

            $games->each(function (Game $game) use (&$count, $yearDelta) {

                UpdatePlayerGameLogsJob::dispatch($game, $yearDelta)->onQueue('stats-integration')->delay($count * 10);
                $count++;
            });
        });
        return $count;
    }

    /**
     * @param LeagueCollection|null $leagues
     * @param bool $force
     * @return GameQueryBuilder
     */
    protected function getGameQuery(LeagueCollection $leagues = null, $force = false)
    {
        $query = Game::query()->whereDate('starts_at', '<', now()->subHours(6));
        if ($leagues) {
            $query = $query->forLeagues($leagues->pluck('id')->toArray());
        }
        if (! $force) {
            $query = $query->whereNull('finalized_at')->where('schedule_status', '=', Game::SCHEDULE_STATUS_NORMAL);
        }
        return $query;
    }
}
