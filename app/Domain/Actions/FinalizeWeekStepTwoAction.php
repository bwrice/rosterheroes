<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;

class FinalizeWeekStepTwoAction
{
    public function execute()
    {
        $week = Week::current();
        $count = Game::query()->withPlayerSpiritsForWeeks([$week->id])->isFinalized(false)->count();
        if ($count) {
            throw new FinalizeWeekException($week, "There are " . $count . " games not finalized", FinalizeWeekException::CODE_GAMES_NOT_FINALIZED);
        }


    }
}
