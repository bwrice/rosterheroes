<?php

namespace Tests\Feature;

use App\Domain\Actions\AddBuildSquadSnapshotJobsToBatch;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\SquadSnapshotFactory;
use App\Jobs\BuildSquadSnapshotJob;
use App\Jobs\BuildSquadSnapshotsForGroupJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AddBuildSquadSnapshotJobsToBatchTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AddBuildSquadSnapshotJobsToBatch
     */
    protected function getDomainAction()
    {
        return app(AddBuildSquadSnapshotJobsToBatch::class);
    }

    /**
     * @test
     */
    public function it_will_add_build_squad_snapshot_jobs_within_the_given_range()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();

        $squads = collect();
        for ($i = 1; $i <= rand(3, 10); $i++) {
            $squad = SquadFactory::new()->create();
            CampaignFactory::new()->withSquadID($squad->id)->withWeekID($currentWeek->id)->create();
            $squads->push($squad);
        }
        $outOfRangeSquad = SquadFactory::new()->create();
        CampaignFactory::new()->withSquadID($outOfRangeSquad->id)->withWeekID($currentWeek->id)->create();

        Queue::fake();

        $batch = Bus::batch([])->dispatch();

        $this->getDomainAction()->execute($batch, $squads->first()->id, $squads->last()->id);

        Queue::assertPushed(BuildSquadSnapshotJob::class, $squads->count());

        foreach ($squads as $squad) {
            Queue::assertPushed(function (BuildSquadSnapshotJob $job) use ($squad) {
                return $job->squad->id === $squad->id;
            });
        }

        Queue::assertNotPushed(function (BuildSquadSnapshotJob $job) use ($outOfRangeSquad) {
            return $job->squad->id === $outOfRangeSquad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_add_build_snapshot_jobs_for_squads_without_a_current_weeks_campaign()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();

        $squads = collect();
        for ($i = 1; $i <= 5; $i++) {
            $squad = SquadFactory::new()->create();
            if ($i != 4) {
                CampaignFactory::new()->withSquadID($squad->id)->withWeekID($currentWeek->id)->create();
            } else {
                $invalidSquad = $squad;
            }
            $squads->push($squad);
        }

        Queue::fake();

        $batch = Bus::batch([])->dispatch();

        $this->getDomainAction()->execute($batch, $squads->first()->id, $squads->last()->id);

        Queue::assertPushed(BuildSquadSnapshotJob::class, $squads->count() - 1);

        Queue::assertNotPushed(function (BuildSquadSnapshotJob $job) use ($invalidSquad) {
            return $job->squad->id === $invalidSquad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_no_add_snapshot_jobs_for_squads_with_existing_current_week_snapshot()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();

        $squads = collect();
        for ($i = 1; $i <= 5; $i++) {
            $squad = SquadFactory::new()->create();
            CampaignFactory::new()->withSquadID($squad->id)->withWeekID($currentWeek->id)->create();
            if ($i == 4) {
                $invalidSquad = $squad;
                SquadSnapshotFactory::new()->withSquadID($squad->id)->withWeekID($currentWeek->id)->create();
            }
            $squads->push($squad);
        }

        Queue::fake();

        $batch = Bus::batch([])->dispatch();

        $this->getDomainAction()->execute($batch, $squads->first()->id, $squads->last()->id);

        Queue::assertPushed(BuildSquadSnapshotJob::class, $squads->count() - 1);

        Queue::assertNotPushed(function (BuildSquadSnapshotJob $job) use ($invalidSquad) {
            return $job->squad->id === $invalidSquad->id;
        });
    }
}
