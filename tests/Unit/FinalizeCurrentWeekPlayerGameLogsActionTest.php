<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Facades\CurrentWeek;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\FinalizeWeekStepTwoJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FinalizeCurrentWeekPlayerGameLogsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var FinalizeCurrentWeekPlayerGameLogsAction */
    protected $domainAction;

    /** @var PlayerSpirit */
    protected $playerSpiritOne;

    /** @var PlayerSpirit */
    protected $playerSpiritTwo;

    /** @var Game */
    protected $gameOne;

    /** @var Game */
    protected $gameTwo;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        CurrentWeek::setTestCurrent($this->week);
        $this->gameOne = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHour()
        ]);
        $this->gameTwo = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHour()
        ]);
        $this->playerSpiritOne = factory(PlayerSpirit::class)->create([
            'game_id' => $this->gameOne->id,
            'week_id' => $this->week->id,
        ]);
        $this->playerSpiritTwo = factory(PlayerSpirit::class)->create([
            'game_id' => $this->gameTwo->id,
            'week_id' => $this->week->id,
        ]);
        Date::setTestNow(CurrentWeek::finalizingStartsAt()->addMinutes(10));
        $this->domainAction = app(FinalizeCurrentWeekPlayerGameLogsAction::class);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_not_long_enough_after_adventuring_ends()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(false);

        try {
            $step = random_int(1, 10);
            $this->domainAction->execute($step);
        } catch (FinalizeWeekException $exception) {
            $this->assertEquals(FinalizeWeekException::INVALID_TIME_TO_FINALIZE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_queue_jobs_to_finalize_stats_for_games_chained_with_step_two()
    {
        Queue::fake();

        $step = random_int(1, 10);
        $nextStep = $step + 1;

        $this->domainAction->execute($step);

        foreach ([
            $this->playerSpiritOne,
            $this->playerSpiritTwo
                 ] as $playerSpirit) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($playerSpirit) {
                return $chainedJob->getDecoratedJob()->getGame()->id === $playerSpirit->game_id;
            });
        }
    }

}
