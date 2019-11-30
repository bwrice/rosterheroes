<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Exceptions\BuildWeekException;
use Illuminate\Support\Facades\Date;

class BuildFirstWeekAction
{
    /**
     * @var BuildWeekAction
     */
    private $buildWeekAction;

    public function __construct(BuildWeekAction $buildWeekAction)
    {
        $this->buildWeekAction = $buildWeekAction;
    }

    public function execute(): Week
    {
        $currentWeek = Week::current();
        if ($currentWeek) {
            throw new BuildWeekException("There should be no current week when building the first week", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }
        $now = Date::now();
        $week = $this->buildWeekAction->execute($now);
        $week->made_current_at = $now;
        $week->save();
        return $week;
    }
}
