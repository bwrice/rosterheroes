<?php

namespace Tests\Unit;

use App\Domain\Models\Game;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_this_weeks_game()
    {
        /** @var \App\Domain\Models\Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);

        /** @var Game $game */
        $game = factory(Game::class)->create([
            'week_id' => $week,
            'starts_at' => $week->everything_locks_at->copy()->addHours(3)
        ]);

        $this->assertEquals($game->id, $game->homeTeam->thisWeeksGame()->id);
        $this->assertEquals($game->id, $game->awayTeam->thisWeeksGame()->id);

        Week::setTestCurrent(); // clear test week
    }
}
