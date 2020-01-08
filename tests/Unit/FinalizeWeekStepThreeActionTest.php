<?php

namespace Tests\Unit;

use App\Domain\Actions\FinalizeWeekStepThreeAction;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Jobs\FinalizeWeekStepFourJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FinalizeWeekStepThreeActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var Hero */
    protected $heroOne;

    /** @var Hero */
    protected $heroTwo;

    /** @var FinalizeWeekStepThreeAction */
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

        $this->domainAction = app(FinalizeWeekStepThreeAction::class);
    }

    /**
    * @test
    */
    public function it_will_dispatch_squad_snapshot_jobs_chained_with_step_four()
    {
        Queue::fake();

        $this->domainAction->execute();

        foreach ([
                     $this->heroOne,
                     $this->heroTwo
                 ] as $hero) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                FinalizeWeekStepFourJob::class
            ], function (AsyncChainedJob $chainedJob) use ($hero) {
                return $chainedJob->getDecoratedJob()->squad->id === $hero->squad_id;
            });
        }
    }
}
