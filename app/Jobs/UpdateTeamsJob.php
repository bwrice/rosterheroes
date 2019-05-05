<?php

namespace App\Jobs;

use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\DataTransferObjects\TeamDTO;
use App\External\Stats\StatsIntegration;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class UpdateTeamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var League
     */
    private $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
     * Execute the job.
     *
     * @param StatsIntegration $integration
     *
     * @return void
     */
    public function handle(StatsIntegration $integration)
    {
        Log::notice("Beginning teams update for League: " . $this->league->abbreviation);

        $teamDTOs = $integration->getTeamDTOs($this->league);
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
