<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\FinalizeWeekStepFiveAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekStepFourAction;
use App\Domain\Models\Minion;
use App\Domain\Models\Titan;
use App\Jobs\FinalizeWeekStepFiveJob;
use App\Jobs\FinalizeWeekStepSixJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FinalizeWeekStepFiveActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var FinalizeWeekStepFourAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->domainAction = app(FinalizeWeekStepFiveAction::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_build_titan_snapshots_chained_with_step_six()
    {
        Queue::fake();

        $this->domainAction->execute();

        Queue::assertPushed(AsyncChainedJob::class, Titan::query()->count());

        Queue::assertPushedWithChain(AsyncChainedJob::class, [
            FinalizeWeekStepSixJob::class
        ]);
    }
}
