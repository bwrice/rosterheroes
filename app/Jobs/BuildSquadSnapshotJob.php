<?php

namespace App\Jobs;

use App\Domain\Actions\BuildSquadSnapshotAction;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSquadSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Squad
     */
    public $squad;

    /**
     * BuildSquadSnapshotsJob constructor.
     * @param Squad $squad
     */
    public function __construct(Squad $squad)
    {
        $this->squad = $squad;
    }

    /**
     * @param BuildSquadSnapshotAction $domainAction
     */
    public function handle(BuildSquadSnapshotAction $domainAction)
    {
        $domainAction->execute($this->squad);
    }
}
