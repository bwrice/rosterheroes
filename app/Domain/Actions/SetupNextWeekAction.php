<?php


namespace App\Domain\Actions;


use App\Domain\Actions\WeekFinalizing\FinalizeWeekDomainAction;
use App\Domain\Models\Game;
use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;

class SetupNextWeekAction implements FinalizeWeekDomainAction
{
    /**
     * @var BuildNewCurrentWeekAction
     */
    private $buildWeekAction;

    public function __construct(BuildNewCurrentWeekAction $buildWeekAction)
    {
        $this->buildWeekAction = $buildWeekAction;
    }

    public function execute(int $finalizeWeekStep, array $extra = [])
    {
        if (! CurrentWeek::exists()) {
            throw new BuildNextWeekException("No current week to build next week from", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }

        return $this->buildWeekAction->execute();
    }
}
