<?php

namespace App\Jobs;

use App\Domain\Teams\Team;
use App\Domain\Teams\TeamDTO;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateTeams implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Execute the job.
     *
     * @param StatsIntegration $integration
     *
     * @return void
     */
    public function handle(StatsIntegration $integration)
    {
        $teamDTOs = $integration->getTeamDTOs();
        $teamDTOs->each(function (TeamDTO $teamDTO) {
            Team::updateOrCreate([
               'external_id' => $teamDTO->getExternalID()
            ], [
                'league_id' => $teamDTO->getLeague()->id,
                'name' => $teamDTO->getName(),
                'location' => $teamDTO->getLocation(),
                'abbreviation' => $teamDTO->getAbbreviation()
            ]);
        });
    }
}
