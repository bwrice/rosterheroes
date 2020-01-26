<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekMinionSnapshotsAction;
use App\Domain\Models\Minion;
use App\Jobs\BuildMinionSnapshotJob;
use App\Jobs\FinalizeWeekStepFiveJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;


class BuildCurrentWeekMinionSnapshotsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var BuildCurrentWeekMinionSnapshotsAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->domainAction = app(BuildCurrentWeekMinionSnapshotsAction::class);
    }

    /**
    * @test
    */
    public function it_will_dispatch_build_minion_snapshot_jobs_chained_with_step_five()
    {
        Queue::fake();

        $this->domainAction->execute();

        Queue::assertPushed(AsyncChainedJob::class, Minion::query()->count());

        Queue::assertPushedWithChain(AsyncChainedJob::class, [
            FinalizeWeekStepFiveJob::class
        ]);
    }
}
