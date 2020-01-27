<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekTitanSnapshotsAction;
use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekMinionSnapshotsAction;
use App\Domain\Models\Minion;
use App\Domain\Models\Titan;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\FinalizeWeekStepFiveJob;
use App\Jobs\SetupNextWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildCurrentWeekTitanSnapshotsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var BuildCurrentWeekTitanSnapshotsAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->domainAction = app(BuildCurrentWeekTitanSnapshotsAction::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_build_titan_snapshots_chained_with_step_six()
    {
        Queue::fake();

        $step = random_int(1, 10);
        $nextStep = $step + 1;

        $this->domainAction->execute($step);

        Queue::assertPushed(AsyncChainedJob::class, Titan::query()->count());

        Queue::assertPushedWithChain(AsyncChainedJob::class, [
            new FinalizeWeekJob($nextStep)
        ]);
    }
}
