<?php


namespace App\Domain\Actions;


use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;
use App\SquadSnapshot;

class BuildSquadSnapshotAction
{
    /** @var Squad */
    protected $squad;

    public function execute(Squad $squad): SquadSnapshot
    {
        $this->squad = $squad;
        if (! CurrentWeek::finalizing()) {
            throw new BuildSquadSnapshotException($this->squad, "Week not in finalizing state", BuildSquadSnapshotException::CODE_WEEK_NOT_FINALIZED);
        }

        /** @var SquadSnapshot $snapshot */
        $snapshot = SquadSnapshot::query()->create([
            'squad_id' => $this->squad->id,
            'week_id' => CurrentWeek::id(),
            'data' => []
        ]);
        return $snapshot;
    }
}
