<?php

namespace App\Jobs;

use App\Domain\Actions\Snapshots\BuildSquadSnapshot;
use App\Domain\Models\Squad;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSquadSnapshotJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Squad $squad;

    public function __construct(Squad $squad)
    {
        $this->squad = $squad;
    }

    /**
     * @param BuildSquadSnapshot $buildSquadSnapshot
     * @throws \Exception
     */
    public function handle(BuildSquadSnapshot $buildSquadSnapshot)
    {
        $buildSquadSnapshot->execute($this->squad);
    }
}
