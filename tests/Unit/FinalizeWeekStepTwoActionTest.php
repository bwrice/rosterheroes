<?php

namespace Tests\Unit;

use App\Domain\Actions\FinalizeWeekStepOneAction;
use App\Domain\Actions\FinalizeWeekStepTwoAction;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\FinalizeWeekException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class FinalizeWeekStepTwoActionTest extends TestCase
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
            'starts_at' => $this->week->adventuring_locks_at->addHour(),
            'finalized_at' => $this->week->adventuring_locks_at->subHours(3)
        ]);
        $this->gameTwo = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHour(),
            'finalized_at' => $this->week->adventuring_locks_at->subHours(3)
        ]);
        $this->playerSpiritOne = factory(PlayerSpirit::class)->create([
            'game_id' => $this->gameOne->id,
            'week_id' => $this->week->id
        ]);
        $this->playerSpiritTwo = factory(PlayerSpirit::class)->create([
            'game_id' => $this->gameTwo->id,
            'week_id' => $this->week->id
        ]);
        Date::setTestNow($this->week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS + 1));
        $this->domainAction = app(FinalizeWeekStepTwoAction::class);
    }
    /**
    * @test
    */
    public function it_will_throw_an_exception_if_any_games_with_player_spirits_are_not_finalized()
    {
        $this->gameOne->finalized_at = null;
        $this->gameOne->save();

        try {
            $this->domainAction->execute($this->week);
        } catch (FinalizeWeekException $exception) {
            $this->assertEquals(FinalizeWeekException::CODE_GAMES_NOT_FINALIZED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
