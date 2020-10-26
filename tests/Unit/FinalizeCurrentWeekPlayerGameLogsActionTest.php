<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekPlayerGameLogsAction;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Facades\CurrentWeek;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\UpdatePlayerGameLogsJob;
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

        $gameOnePlayerGameLogFactory = PlayerGameLogFactory::new()->forGame($this->gameOne);
        $this->playerSpiritOne = PlayerSpiritFactory::new()
            ->withPlayerGameLog($gameOnePlayerGameLogFactory)
            ->forWeek($this->week)
            ->create();

        $gameTwoPlayerGameLogFactory = PlayerGameLogFactory::new()->forGame($this->gameTwo);
        $this->playerSpiritTwo = PlayerSpiritFactory::new()
            ->withPlayerGameLog($gameTwoPlayerGameLogFactory)
            ->forWeek($this->week)
            ->create();

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
            $step = rand(1, 10);
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
    public function it_will_queue_batched_jobs_to_finalize_stats_for_games()
    {
        Queue::fake();

        $step = rand(1, 10);
        $this->domainAction->execute($step);

        foreach ([
            $this->playerSpiritOne,
            $this->playerSpiritTwo
                 ] as $playerSpirit) {

            /** @var PlayerSpirit $playerSpirit */
            Queue::assertPushed(function (UpdatePlayerGameLogsJob $job) use ($playerSpirit) {
                return $job->getGame()->id === $playerSpirit->playerGameLog->game_id;
            });
        }
    }

    /**
     * @test
     */
    public function it_will_dispatch_finalize_week_job_with_next_step()
    {
        Queue::fake();

        $step = rand(1, 10);
        $this->domainAction->execute($step);

        Queue::assertPushed(function (FinalizeWeekJob $job) use ($step) {
            return $job->step = $step + 1;
        });
    }

}
