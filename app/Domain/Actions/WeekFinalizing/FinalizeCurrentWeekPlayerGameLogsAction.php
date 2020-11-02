<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Exceptions\FinalizeWeekException;
use App\Facades\CurrentWeek;
use App\Jobs\UpdatePlayerGameLogsJob;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class FinalizeCurrentWeekPlayerGameLogsAction extends BatchedWeeklyAction
{
    protected string $name = 'Finalize Player Game Logs';

    protected function jobs(): Collection
    {
        if (! CurrentWeek::finalizing()) {
            throw new FinalizeWeekException(CurrentWeek::get(), "Week is not ready to be finalized", FinalizeWeekException::INVALID_TIME_TO_FINALIZE);
        }

        $currentWeekID = CurrentWeek::id();
        $games = Game::query()->withPlayerSpiritsForWeeks([$currentWeekID])->get();
        $jobs = $games->map(function (Game $game) {
            return new UpdatePlayerGameLogsJob($game);
        });
        $now = now();
        $secondsDelay = 0;
        return $jobs->each(function (UpdatePlayerGameLogsJob $job) use ($now, &$secondsDelay) {
            /** @var CarbonInterface $delay */
            $delay = $now->addSeconds($secondsDelay);
            $job->delay($delay);
            $secondsDelay += 15;
        });
    }
}
