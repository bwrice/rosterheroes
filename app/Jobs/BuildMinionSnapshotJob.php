<?php

namespace App\Jobs;

use App\Domain\Actions\Snapshots\BuildMinionSnapshot;
use App\Domain\Models\Minion;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildMinionSnapshotJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Minion $minion;

    /**
     * BuildMinionSnapshotJob constructor.
     * @param Minion $minion
     */
    public function __construct(Minion $minion)
    {
        $this->minion = $minion;
    }

    /**
     * @param BuildMinionSnapshot $buildMinionSnapshot
     * @throws \Exception
     */
    public function handle(BuildMinionSnapshot $buildMinionSnapshot)
    {
        $buildMinionSnapshot->execute($this->minion);
    }
}
