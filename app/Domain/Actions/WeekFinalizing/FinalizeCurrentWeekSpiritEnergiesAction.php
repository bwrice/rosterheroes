<?php


namespace App\Domain\Actions\WeekFinalizing;


use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Jobs\FinalizeWeekStepThreeJob;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;

class FinalizeCurrentWeekSpiritEnergiesAction
{
    public function execute()
    {
        $week = Week::current();
        $count = Game::query()->withPlayerSpiritsForWeeks([$week->id])->isFinalized(false)->count();
        if ($count) {
            throw new FinalizeWeekException($week, "There are " . $count . " games not finalized", FinalizeWeekException::CODE_GAMES_NOT_FINALIZED);
        }

        UpdatePlayerSpiritEnergiesJob::withChain([
            new FinalizeWeekStepThreeJob()
        ])->dispatch();
    }
}
