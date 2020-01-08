<?php

namespace App\Jobs;

use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSquadSnapshotsJob implements ShouldQueue
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
