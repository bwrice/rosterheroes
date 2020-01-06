<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;

class FinalizeWeekStepTwoAction
{
    public function execute(Week $week)
    {
        $count = Game::query()->withPlayerSpiritsForWeeks([$week->id])->isFinalized(false)->count();
        if ($count) {
            throw new FinalizeWeekException($week, "There are " . $count . " games not finalized", FinalizeWeekException::CODE_GAMES_NOT_FINALIZED);
        }
    }
}
