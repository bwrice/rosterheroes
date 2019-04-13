<?php

namespace App\Jobs;

use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateGames implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @param StatsIntegration $statsIntegration
     */
    public function handle(StatsIntegration $statsIntegration)
    {
        $gameDTOs = $statsIntegration->getGameDTOs();
    }
}
