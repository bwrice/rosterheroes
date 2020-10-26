<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Exceptions\FinalizeWeekException;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\UpdatePlayerGameLogsJob;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Bus;

class FinalizeCurrentWeekPlayerGameLogsAction implements FinalizeWeekDomainAction
{
    /**
     * @param int $finalizeWeekStep
     * @param array $extra
     * @throws \Throwable
     */
    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        if (! CurrentWeek::finalizing()) {
            throw new FinalizeWeekException(CurrentWeek::get(), "Week is not ready to be finalized", FinalizeWeekException::INVALID_TIME_TO_FINALIZE);
        }
        $jobs = $this->getUpdatePlayerGameLogsForGameJobs();
        Bus::Batch($jobs->toArray())->then(function () use ($finalizeWeekStep) {
            FinalizeWeekJob::dispatch($finalizeWeekStep);
        })->dispatch();
    }

    protected function getUpdatePlayerGameLogsForGameJobs()
    {
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
