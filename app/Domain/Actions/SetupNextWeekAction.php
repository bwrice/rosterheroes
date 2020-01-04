<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;

class SetupNextWeekAction
{
    /**
     * @var BuildWeekAction
     */
    private $buildWeekAction;

    public function __construct(BuildWeekAction $buildWeekAction)
    {
        $this->buildWeekAction = $buildWeekAction;
    }

    public function execute()
    {
        $currentWeek = Week::current();
        if (! $currentWeek) {
            throw new BuildNextWeekException("No current week to build next week from", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }

        $count = Game::query()->withPlayerSpiritsForWeeks([$currentWeek->id])->isFinalized(false)->count();
        if ($count) {
            throw new BuildNextWeekException($count . " games have player spirits and are not finalized", BuildNextWeekException::CODE_GAMES_NOT_FINALIZED);
        }
        $nextWeek = $this->buildWeekAction->execute($currentWeek->adventuring_locks_at);
    }
}
