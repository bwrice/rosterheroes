<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\BuildWeeklyMinionSnapshots;
use App\Domain\Models\Minion;
use App\Domain\Models\Week;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Jobs\BuildMinionSnapshotJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildWeeklyMinionSnapshotsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildWeeklyMinionSnapshots
     */
    protected function getDomainAction()
    {
        return app(BuildWeeklyMinionSnapshots::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_a_build_minion_snapshot_job_for_minions_without_a_weekly_snapshot()
    {
        $week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $minion = MinionFactory::new()->create();

        Queue::fake();

        $this->getDomainAction()->execute(1);

        Queue::assertPushed(function (BuildMinionSnapshotJob $job) use ($minion) {
            return $job->minion->id === $minion->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_a_build_minion_snapshot_job_for_a_minion_with_an_existing_weekly_snapshot()
    {
        $week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $minion = MinionFactory::new()->create();
        $minionSnapshot = MinionSnapshotFactory::new()->withMinionID($minion->id)->withWeekID($week->id)->create();

        Queue::fake();

        $this->getDomainAction()->execute(1);

        Queue::assertNotPushed(function (BuildMinionSnapshotJob $job) use ($minion) {
            return $job->minion->id === $minion->id;
        });
    }
}
