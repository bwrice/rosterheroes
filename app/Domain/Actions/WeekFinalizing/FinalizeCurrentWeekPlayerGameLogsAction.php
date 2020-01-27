<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Exceptions\FinalizeWeekException;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\FinalizeWeekStepTwoJob;
use App\Jobs\UpdatePlayerGameLogsJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;

class FinalizeCurrentWeekPlayerGameLogsAction implements FinalizeWeekDomainAction
{
    public function execute(int $step)
    {
        if (! CurrentWeek::finalizing()) {
            throw new FinalizeWeekException(CurrentWeek::get(), "Week is not ready to be finalized", FinalizeWeekException::INVALID_TIME_TO_FINALIZE);
        }
        ChainGroup::create($this->getUpdatePlayerGameLogsForGameJobs(), [
            new FinalizeWeekJob($step + 1)
        ])->dispatch();
    }

    protected function getUpdatePlayerGameLogsForGameJobs()
    {
        $currentWeekID = CurrentWeek::id();
        $games = Game::query()->withPlayerSpiritsForWeeks([$currentWeekID])->get();
        return $games->map(function (Game $game) {
            return new UpdatePlayerGameLogsJob($game);
        })->toArray();
    }
}
