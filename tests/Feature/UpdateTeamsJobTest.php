<?php

namespace Tests\Feature;

use App\Domain\Models\Team;
use App\Domain\DataTransferObjects\TeamDTO;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdateTeamsJob;
use App\Domain\Models\League;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTeamsJobTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function the_job_will_update_teams()
    {
        $mlb = League::mlb();

        $externalID1 = uniqid();
        $DTO1 = new TeamDTO($mlb, 'Test Team One', 'Anywhere Town', 'TTO', $externalID1);
        $externalID2 = uniqid();
        $DTO2 = new TeamDTO($mlb, 'Test Team Two', 'Anywhere City', 'TTW', $externalID2);

        $teamDTOs = collect([$DTO1, $DTO2]);

        $integration = new MockIntegration($teamDTOs);
        app()->instance(StatsIntegration::class, $integration);

        UpdateTeamsJob::dispatchNow($mlb);

        $integrationType = $integration->getIntegrationType();

        $team = Team::query()->whereHas('externalTeams', function (Builder $builder) use ($integrationType) {
            return $builder->where('integration_type_id', '=', $integrationType->id);
        })->first();
        $this->assertNotNull($team);
        $team = Team::query()->whereHas('externalTeams', function (Builder $builder) use ($integrationType) {
            return $builder->where('integration_type_id', '=', $integrationType->id);
        })->first();
        $this->assertNotNull($team);
    }
}
