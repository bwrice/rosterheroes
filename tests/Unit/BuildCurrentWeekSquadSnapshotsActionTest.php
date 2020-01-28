<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\BuildCurrentWeekSquadSnapshotsAction;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Facades\HeroService;
use App\Facades\SquadService;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildCurrentWeekSquadSnapshotsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var Hero */
    protected $heroOne;

    /** @var Hero */
    protected $heroTwo;

    /** @var BuildCurrentWeekSquadSnapshotsAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->create();
        Week::setTestCurrent($this->week);

        $this->heroOne = factory(Hero::class)->create([
            'player_spirit_id' => factory(PlayerSpirit::class)->create([
                'week_id' => $this->week
            ])
        ]);

        $this->heroTwo = factory(Hero::class)->create([
            'player_spirit_id' => factory(PlayerSpirit::class)->create([
                'week_id' => $this->week
            ])
        ]);

        $this->domainAction = app(BuildCurrentWeekSquadSnapshotsAction::class);
    }

    /**
    * @test
    */
    public function it_will_dispatch_squad_snapshot_jobs_chained_with_step_four()
    {
        Queue::fake();
        SquadService::partialMock()->shouldReceive('combatReady')->andReturn(true);

        $step = random_int(1, 10);
        $nextStep = $step + 1;

        $this->domainAction->execute($step);

        foreach ([
                     $this->heroOne,
                     $this->heroTwo
                 ] as $hero) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($hero) {
                return $chainedJob->getDecoratedJob()->squad->id === $hero->squad_id;
            });
        }
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_job_for_squad_if_squad_not_ready_for_combat()
    {
        Queue::fake();
        SquadService::partialMock()->shouldReceive('combatReady')->andReturnUsing(function (Squad $squad) {
            if ($squad->id === $this->heroOne->squad_id) {
                return true;
            }
            return false;
        });

        $step = random_int(1, 10);
        $nextStep = $step + 1;

        $this->domainAction->execute($step);

        Queue::assertPushedWithChain(AsyncChainedJob::class, [
            new FinalizeWeekJob($nextStep)
        ], function (AsyncChainedJob $chainedJob) {
            return $chainedJob->getDecoratedJob()->squad->id === $this->heroOne->squad_id;
        });

        Queue::assertNotPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) {
            return $chainedJob->getDecoratedJob()->squad->id === $this->heroTwo->squad_id;
        });
    }
}
