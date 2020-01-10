<?php

namespace Tests\Unit;

use App\Domain\Actions\FinalizeWeekStepFourAction;
use App\Domain\Models\Minion;
use App\Jobs\BuildMinionSnapshotJob;
use App\Jobs\FinalizeWeekStepFiveJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;


class FinalizeWeekStepFourActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var FinalizeWeekStepFourAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->domainAction = app(FinalizeWeekStepFourAction::class);
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
