<?php

namespace Tests\Unit;

use App\Domain\Actions\WeekFinalizing\FinalizeCurrentWeekSpiritEnergiesAction;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Jobs\FinalizeWeekJob;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FinalizeCurrentWeekSpiritEnergiesActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var FinalizeCurrentWeekSpiritEnergiesAction */
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
            'starts_at' => $this->week->adventuring_locks_at->addHour(),
            'finalized_at' => $this->week->adventuring_locks_at->subHours(3)
        ]);
        $this->gameTwo = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHour(),
            'finalized_at' => $this->week->adventuring_locks_at->subHours(3)
        ]);

        $gameOnePlayerGameLogFactory = PlayerGameLogFactory::new()->forGame($this->gameOne);
        $this->playerSpiritOne = PlayerSpiritFactory::new()
            ->withPlayerGameLog($gameOnePlayerGameLogFactory)
            ->forWeek($this->week);

        $gameTwoPlayerGameLogFactory = PlayerGameLogFactory::new()->forGame($this->gameTwo);
        $this->playerSpiritOne = PlayerSpiritFactory::new()
            ->withPlayerGameLog($gameTwoPlayerGameLogFactory)
            ->forWeek($this->week);

        Date::setTestNow($this->week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS + 1));
        $this->domainAction = app(FinalizeCurrentWeekSpiritEnergiesAction::class);
    }
    /**
    * @test
    */
    public function it_will_throw_an_exception_if_any_games_with_player_spirits_are_not_finalized()
    {
        $this->gameOne->finalized_at = null;
        $this->gameOne->save();

        try {
            $step = rand(1, 3);
            $this->domainAction->execute($step);
        } catch (FinalizeWeekException $exception) {
            $this->assertEquals(FinalizeWeekException::CODE_GAMES_NOT_FINALIZED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_queue_update_spirits_and_step_three_jobs_in_a_chain()
    {
        Queue::fake();

        $step = rand(1, 3);
        $nextStep = $step + 1;

        $this->domainAction->execute($step);

        Queue::assertPushedWithChain(UpdatePlayerSpiritEnergiesJob::class, [
            new FinalizeWeekJob($nextStep)
        ]);
    }
}
