<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\Date;

class BuildInitialWeekAction
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

    public function execute(): Week
    {
        if (CurrentWeek::exists()) {
            throw new BuildWeekException("There should be no current week when building the first week", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }
        $week = $this->buildWeekAction->execute();
        $week->made_current_at = Date::now();
        $week->save();
        $this->buildWeeklyPlayerSpiritsAction->execute($week);
        return $week;
    }
}
