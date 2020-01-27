<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\FinalizeWeekStepThreeJob;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;

class FinalizeCurrentWeekSpiritEnergiesAction implements FinalizeWeekDomainAction
{
    public function execute(int $step)
    {
        $week = Week::current();
        $count = Game::query()->withPlayerSpiritsForWeeks([$week->id])->isFinalized(false)->count();
        if ($count) {
            throw new FinalizeWeekException($week, "There are " . $count . " games not finalized", FinalizeWeekException::CODE_GAMES_NOT_FINALIZED);
        }

        UpdatePlayerSpiritEnergiesJob::withChain([
            new FinalizeWeekJob($step + 1)
        ])->dispatch();
    }
}
