<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekSkirmishSnapshotsAction;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Week;
use App\Facades\SkirmishService;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildCurrentWeekSkirmishSnapshotsActionTest extends TestCase
{
    /** @var Week */
    protected $week;

    /** @var Collection */
    protected $skirmishes;

    /** @var int */
    protected $currentStep;

    /** @var BuildCurrentWeekSkirmishSnapshotsAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $this->skirmishes = factory(Skirmish::class, 3)->create();
        $mockedQuery = Skirmish::query()->whereIn('id', $this->skirmishes->pluck('id')->toArray());
        $this->currentStep = random_int(1, 9);
        $this->domainAction = app(BuildCurrentWeekSkirmishSnapshotsAction::class);
        SkirmishService::partialMock()->shouldReceive('query')->andReturn($mockedQuery);
    }

    /**
     * @test
     */
    public function it_will_dispatch_build_skirmish_snapshot_jobs_chained_with_finalize_week_next_step()
    {
        Queue::fake();
        $this->domainAction->execute($this->currentStep);
        $nextStep = $this->currentStep + 1;

        Queue::assertPushed(AsyncChainedJob::class, $this->skirmishes->count());

        foreach ($this->skirmishes as $skirmish) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($skirmish) {
                return $chainedJob->getDecoratedJob()->skirmish->id === $skirmish->id;
            });
        }
    }
}
