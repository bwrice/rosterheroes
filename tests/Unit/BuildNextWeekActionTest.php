<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildNextWeekAction;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildNextWeekActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->create([
            'adventuring_locks_at' => Date::now()->subHours(16)
        ]);
        Week::setTestCurrent($this->currentWeek);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_there_is_no_current_week()
    {
        Week::setTestCurrent(null);

        /** @var BuildNextWeekAction $domainAction */
        $domainAction = app(BuildNextWeekAction::class);

        try {
            $domainAction->execute();
        } catch (BuildNextWeekException $exception) {
            $this->assertEquals(BuildNextWeekException::CODE_INVALID_CURRENT_WEEK, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_player_spirit_games_are_not_finalized()
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->currentWeek->id
        ]);

        $this->assertNull($playerSpirit->game->finalized_at);

        /** @var BuildNextWeekAction $domainAction */
        $domainAction = app(BuildNextWeekAction::class);

        try {
            $domainAction->execute();
        } catch (BuildNextWeekException $exception) {
            $this->assertEquals(BuildNextWeekException::CODE_GAMES_NOT_FINALIZED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

}
