<?php

namespace Tests\Feature;

use App\Domain\Teams\Team;
use App\Domain\Teams\TeamDTO;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdateTeams;
use App\League;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateSquadsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_update_teams()
    {
        $externalID1 = uniqid();
        $DTO1 = new TeamDTO(League::where('abbreviation', '=', League::NFL)->first(), 'Test Team One', 'Anywhere Town', 'TTO', $externalID1);
        $externalID2 = uniqid();
        $DTO2 = new TeamDTO(League::where('abbreviation', '=', League::NFL)->first(), 'Test Team Two', 'Anywhere City', 'TTW', $externalID2);

        $teamDTOs = collect([$DTO1, $DTO2]);

        $integration = new MockIntegration($teamDTOs);
        app()->instance(StatsIntegration::class, $integration);

        /** @var UpdateTeams $job */
        $job = app(UpdateTeams::class);
        $job->handle();

        $team = Team::where('external_id', '=', $externalID1)->first();
        $this->assertNotNull($team);
        $team = Team::where('external_id', '=', $externalID2)->first();
        $this->assertNotNull($team);
    }
}
