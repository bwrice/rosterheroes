<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\BuildWeeklySideQuestSnapshots;
use App\Domain\Models\Week;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestSnapshotFactory;
use App\Jobs\BuildMinionSnapshotJob;
use App\Jobs\BuildSideQuestSnapshotJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildWeeklySideQuestSnapshotsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildWeeklySideQuestSnapshots
     */
    protected function getDomainAction()
    {
        return app(BuildWeeklySideQuestSnapshots::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_a_build_minion_snapshot_job_for_minions_without_a_weekly_snapshot()
    {
        $week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $sideQuest = SideQuestFactory::new()->create();

        Queue::fake();

        $this->getDomainAction()->execute(1);

        Queue::assertPushed(function (BuildSideQuestSnapshotJob $job) use ($sideQuest) {
            return $job->sideQuest->id === $sideQuest->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_a_build_minion_snapshot_job_for_a_minion_with_an_existing_weekly_snapshot()
    {
        $week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $sideQuest = SideQuestFactory::new()->create();
        $sideQuestSnapshot = SideQuestSnapshotFactory::new()->withSideQuestID($sideQuest->id)->withWeekID($week->id)->create();

        Queue::fake();

        $this->getDomainAction()->execute(1);

        Queue::assertNotPushed(function (BuildMinionSnapshotJob $job) use ($sideQuest) {
            return $job->minion->id === $sideQuest->id;
        });
    }
}
