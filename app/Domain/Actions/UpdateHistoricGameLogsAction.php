<?php


namespace App\Domain\Actions;


use App\Domain\Collections\GameCollection;
use App\Domain\Models\Game;
use App\Domain\QueryBuilders\GameQueryBuilder;
use App\Jobs\UpdatePlayerGameLogsJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class UpdateHistoricGameLogsAction
{
    /**
     * @param Collection|null $leagues
     * @param bool $force
     * @param int $yearDelta
     * @return int
     */
    public function execute(Collection $leagues = null, $force = false, int $yearDelta = 0): int
    {
        $count = 0;
        $now = now();
        $this->getGameQuery($leagues, $force)->chunk(100, function (GameCollection $games) use (&$count, $yearDelta, $now) {

            $games->each(function (Game $game) use (&$count, $yearDelta, $now) {

                UpdatePlayerGameLogsJob::dispatch($game, $yearDelta)->onQueue('stats-integration')->delay($now->clone()->addSeconds($count * 10));
                $count++;
            });
        });
        return $count;
    }

    /**
     * @param Collection|null $leagues
     * @param bool $force
     * @return GameQueryBuilder
     */
    protected function getGameQuery(Collection $leagues = null, $force = false)
    {
        $query = Game::query()->where('starts_at', '<', now()->subHours(8));
        if ($leagues) {
            $query = $query->forLeagues($leagues->pluck('id')->toArray());
        }
        if (! $force) {
            $query = $query->whereNull('finalized_at');
        }
        return $query;
    }
}
