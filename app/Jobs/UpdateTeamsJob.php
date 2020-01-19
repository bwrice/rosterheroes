<?php

namespace App\Jobs;

use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\DataTransferObjects\TeamDTO;
use App\External\Stats\StatsIntegration;
use App\StatsIntegrationType;
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
    /**
     * @var int
     */
    private $yearDelta;

    public function __construct(League $league, int $yearDelta = 0)
    {
        if ( $yearDelta > 0 ) {
            throw new \RuntimeException("Year delta must be negative, " . $yearDelta . " was passed");
        }
        $this->league = $league;
        $this->yearDelta = $yearDelta;
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
        // TODO: convert into domain action
        $integrationType = $integration->getIntegrationType();
        $count = 0;

        $teamDTOs = $integration->getTeamDTOs($this->league, $this->yearDelta);
        $teamDTOs->each(function (TeamDTO $teamDTO) use ($integrationType, &$count) {

            /** @var Team $team */
            $team = Team::query()->where('league_id', '=', $teamDTO->getLeague()->id)
                ->where('name', '=', $teamDTO->getName())->first();

            if (! $team) {
                $team = Team::query()->create([
                    'league_id' => $teamDTO->getLeague()->id,
                    'name' => $teamDTO->getName(),
                    'location' => $teamDTO->getLocation(),
                    'abbreviation' => $teamDTO->getAbbreviation()
                ]);
                $count++;
            } else {
                $team = $team->update([
                    'location' => $teamDTO->getLocation(),
                    'abbreviation' => $teamDTO->getAbbreviation()
                ]);
            }

            $team->externalTeams()->updateOrCreate([
                'integration_type_id' => $integrationType->id,
                'team_id' => $team->id,
            ], [
                'external_id' => $teamDTO->getExternalID()
            ]);
        });
        if ($count > 0) {
            Log::alert($count . " new teams created for league: " . $this->league->abbreviation);
        }
    }
}
