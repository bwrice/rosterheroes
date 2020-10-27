<?php

namespace App\Jobs;

use App\Domain\Actions\AddBuildSquadSnapshotJobsToBatch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSquadSnapshotsForGroupJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public int $startRangeID;
    public int $endRangeID;

    public function __construct(int $startRangeID, int $endRangeID)
    {
        $this->startRangeID = $startRangeID;
        $this->endRangeID = $endRangeID;
    }

    /**
     * @param AddBuildSquadSnapshotJobsToBatch $addBuildSquadSnapshotJobsToBatch
     */
    public function handle(AddBuildSquadSnapshotJobsToBatch $addBuildSquadSnapshotJobsToBatch)
    {
        $addBuildSquadSnapshotJobsToBatch->execute($this->batch(), $this->startRangeID, $this->endRangeID);
    }
}
