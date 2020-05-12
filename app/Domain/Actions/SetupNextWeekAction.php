<?php


namespace App\Domain\Actions;


use App\Domain\Actions\WeekFinalizing\FinalizeWeekDomainAction;
use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\Date;

class SetupNextWeekAction
{
    /**
     * @var BuildNewCurrentWeekAction
     */
    protected $buildWeekAction;
    /**
     * @var BuildWeeklyPlayerSpiritsAction
     */
    protected $buildWeeklyPlayerSpiritsAction;

    public function __construct(
        BuildNewCurrentWeekAction $buildWeekAction,
        BuildWeeklyPlayerSpiritsAction $buildWeeklyPlayerSpiritsAction)
    {
        $this->buildWeekAction = $buildWeekAction;
        $this->buildWeeklyPlayerSpiritsAction = $buildWeeklyPlayerSpiritsAction;
    }

    public function execute()
    {
        if (! CurrentWeek::exists()) {
            throw new BuildNextWeekException("No current week to build next week from", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }

        $nextWeek = $this->buildWeekAction->execute();
        $this->buildWeeklyPlayerSpiritsAction->execute($nextWeek);
        $nextWeek->made_current_at = Date::now()->addMinutes(30); // Give a little time for all the jobs to catch-up
        $nextWeek->save();
        return $nextWeek;
    }
}
