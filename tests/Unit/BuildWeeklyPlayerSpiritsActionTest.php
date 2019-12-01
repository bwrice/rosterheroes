<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildWeeklyPlayerSpiritsAction;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Jobs\CreatePlayerSpiritJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildWeeklyPlayerSpiritsActionTest extends TestCase
{
    /** @var Week */
    protected $week;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->state('as-current')->create();
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_there_are_no_valid_games()
    {
        try {
            /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
            $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
            $domainAction->execute($this->week);
        } catch (\Exception $exception) {
            // so we don't get a warning
            $this->assertTrue(true);
            return;
        }

        $this->fail("Exception not throw");
    }

    /**
    * @test
    */
    public function it_will_queue_jobs_for_player_spirit_creation_for_valid_games()
    {
        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create();
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'team_id' => factory(Team::class)->create([
                'league_id' => $playerOne->team->league->id
            ])
        ]);

        $game = factory(Game::class)->create([
            'home_team_id' => $playerOne->team->id,
            'away_team_id' => $playerTwo->team->id,
            'starts_at' => $this->week->adventuring_locks_at->addMinutes(15)
        ]);

        Queue::fake();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerOne, $game) {
            return $job->player->id === $playerOne->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerTwo, $game) {
            return $job->player->id === $playerTwo->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });
    }
}
