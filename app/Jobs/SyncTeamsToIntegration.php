<?php

namespace App\Jobs;

use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncTeamsToIntegration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var StatsIntegration
     */
    private $integration;

    /**
     * SyncTeamsToIntegration constructor.
     * @param StatsIntegration $integration
     */
    public function __construct( StatsIntegration $integration )
    {
        $this->integration = $integration;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->integration->syncTeams();
    }
}
