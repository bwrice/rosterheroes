<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekAttackSnapshotActions;
use App\Domain\Collections\AttackCollection;
use App\Domain\Models\Attack;
use App\Domain\Models\Week;
use App\Facades\AttackService;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildCurrentWeekAttackSnapshotActionsTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Attack */
    protected $attackOne;

    /** @var Attack */
    protected $attackTwo;

    /** @var AttackCollection */
    protected $attacks;

    /** @var Week */
    protected $week;

    /** @var BuildCurrentWeekAttackSnapshotActions */
    protected $domainAction;

    /** @var int */
    protected $step;

    public function setUp(): void
    {
        parent::setUp();

        $this->attacks = factory(Attack::class, 2)->create();
        $this->week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $mockedQuery = Attack::query()->whereIn('id', $this->attacks->pluck('id')->toArray());
        AttackService::partialMock()->shouldReceive('query')->andReturn($mockedQuery);
        $this->domainAction = app(BuildCurrentWeekAttackSnapshotActions::class);
        $this->step = random_int(1, 9);
    }

    /**
     * @test
     */
    public function it_will_dispatch_jobs_to_build_attack_snapshots_chained_with_finalize_week_next_step()
    {
        Queue::fake();
        $this->domainAction->execute($this->step);
        $nextStep = $this->step + 1;

        Queue::assertPushed(AsyncChainedJob::class, 2);

        foreach ($this->attacks as $attack) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($attack) {
                return $chainedJob->getDecoratedJob()->attack->id === $attack->id;
            });
        }
    }
}
