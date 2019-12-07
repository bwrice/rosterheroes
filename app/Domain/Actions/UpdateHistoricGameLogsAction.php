<?php


namespace App\Domain\Actions;


use App\Domain\Collections\GameCollection;
use App\Domain\Collections\LeagueCollection;
use App\Domain\Models\Game;
use App\Jobs\UpdateHistoricPlayerGameLogsJob;
use Illuminate\Support\Facades\Date;

class UpdateHistoricGameLogsAction
{
    public const DAYS_BEFORE_QUERY_ARG = 2;

    /**
     * @param LeagueCollection $leagues
     * @param int $yearDelta
     * @return int
     */
    public function execute(LeagueCollection $leagues = null, int $yearDelta = 0): int
    {
        $count = 0;
        $this->getGameQuery($leagues)->chunk(100, function (GameCollection $games) use (&$count, $yearDelta) {

            $games->each(function (Game $game) use (&$count, $yearDelta) {

                UpdateHistoricPlayerGameLogsJob::dispatch($game, $yearDelta)->onQueue('stats-integration')->delay($count * 5);
                $count++;
            });
        });
        return $count;
    }

    protected function getGameQuery(LeagueCollection $leagues = null)
    {
        $query = Game::query()->whereDate('starts_at', '<', Date::now()->subDays(self::DAYS_BEFORE_QUERY_ARG));
        if ($leagues) {
            $query = $query->forLeagues($leagues->pluck('id')->toArray());
        }
        return $query;
    }
}
