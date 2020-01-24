<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Models\Squad;
use App\Exceptions\AutoManageSquadException;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\Date;

class AutoManageSquadAction
{
    public function execute(Squad $squad)
    {
        if (! CurrentWeek::get()) {
            throw new AutoManageSquadException($squad, 'No current week', AutoManageSquadException::CODE_NO_CURRENT_WEEK);
        }
        if (CurrentWeek::adventuringLocksAt()->isBefore(Date::now()->addHours(4))) {
            throw new AutoManageSquadException($squad, 'Current week locks too soon', AutoManageSquadException::CODE_CURRENT_WEEK_LOCKS_SOON);
        }
    }
}
