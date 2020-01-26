<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Exceptions\FinalizeWeekException;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekStepTwoJob;
use App\Jobs\UpdatePlayerGameLogsJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;

class FinalizeCurrentWeekPlayerGameLogsAction
{
    public function execute()
    {
        if (! CurrentWeek::finalizing()) {
            throw new FinalizeWeekException(CurrentWeek::get(), "Week is not ready to be finalized", FinalizeWeekException::INVALID_TIME_TO_FINALIZE);
        }
        ChainGroup::create($this->getUpdatePlayerGameLogsForGameJobs(), [
            new FinalizeWeekStepTwoJob()
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
