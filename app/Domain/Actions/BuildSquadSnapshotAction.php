<?php


namespace App\Domain\Actions;


use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;

class BuildSquadSnapshotAction
{
    /** @var Squad */
    protected $squad;

    public function execute(Squad $squad)
    {
        $this->squad = $squad;
        if (! CurrentWeek::finalizing()) {
            throw new BuildSquadSnapshotException($this->squad, "Week not in finalizing state", BuildSquadSnapshotException::CODE_WEEK_NOT_FINALIZED);
        }
    }
}
