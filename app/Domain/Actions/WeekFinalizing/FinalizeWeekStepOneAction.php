<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Jobs\FinalizeWeekStepTwoJob;
use App\Jobs\UpdatePlayerGameLogsJob;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Support\Facades\Date;

class FinalizeWeekStepOneAction
{
    public function execute()
    {
        $week = Week::current();
        $this->validateTime($week);
        ChainGroup::create($this->getUpdatePlayerGameLogsForGameJobs($week), [
            new FinalizeWeekStepTwoJob()
        ])->dispatch();
    }

    protected function validateTime(Week $week)
    {
        if (Date::now()->isBefore($week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS))) {
            throw new FinalizeWeekException($week, "Week is not ready to be finalized", FinalizeWeekException::INVALID_TIME_TO_FINALIZE);
        }
    }

    protected function getUpdatePlayerGameLogsForGameJobs(Week $week)
    {
        $games = Game::query()->withPlayerSpiritsForWeeks([$week->id])->get();
        return $games->map(function (Game $game) {
            return new UpdatePlayerGameLogsJob($game);
        })->toArray();
    }
}
