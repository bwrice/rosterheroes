<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;

class BuildInitialWeekAction
{
    /**
     * @var BuildNewCurrentWeekAction
     */
    private $buildWeekAction;

    public function __construct(BuildNewCurrentWeekAction $buildWeekAction)
    {
        $this->buildWeekAction = $buildWeekAction;
    }

    public function execute(): Week
    {
        if (CurrentWeek::exists()) {
            throw new BuildWeekException("There should be no current week when building the first week", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }
        $week = $this->buildWeekAction->execute();
        return $week;
    }
}
