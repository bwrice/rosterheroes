<?php

namespace Tests\Unit;

use App\Domain\Actions\FinalizeWeekStepOneAction;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\StepOneException;
use App\Jobs\FinalizeWeekStepTwoJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class FinalizeWeekStepOneActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var FinalizeWeekStepOneAction */
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
        Week::setTestCurrent($this->week);
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
        Date::setTestNow($this->week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS + 1));
        $this->domainAction = app(FinalizeWeekStepOneAction::class);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_not_long_enough_after_adventuring_ends()
    {
        Date::setTestNow($this->week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS - 1));

        try {
            $this->domainAction->execute($this->week);
        } catch (StepOneException $exception) {
            $this->assertEquals(StepOneException::INVALID_TIME_TO_FINALIZE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_queue_jobs_to_finalize_stats_for_games_chained_with_step_two()
    {
        \Illuminate\Support\Facades\Queue::fake();

        $this->domainAction->execute($this->week);

        foreach ([
            $this->playerSpiritOne,
            $this->playerSpiritTwo
                 ] as $playerSpirit) {

            \Illuminate\Support\Facades\Queue::assertPushed(AsyncChainedJob::class);

            \Illuminate\Support\Facades\Queue::assertPushedWithChain(AsyncChainedJob::class, [
                FinalizeWeekStepTwoJob::class
            ], function (AsyncChainedJob $chainedJob) use ($playerSpirit) {
                return $chainedJob->getDecoratedJob()->getGame()->id === $playerSpirit->game_id;
            });
        }
    }

}
