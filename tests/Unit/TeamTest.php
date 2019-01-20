<?php

namespace Tests\Unit;

use App\Game;
use App\Team;
use App\Weeks\Week;
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
        /** @var Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);

        /** @var Team $homeTeam */
        $homeTeam = Team::query()->inRandomOrder()->first();
        $sportID = $homeTeam->sport->id;

        /** @var Team $awayTeam */
        $awayTeam = Team::query()->whereHas('sport', function(Builder $builder) use ($sportID) {
            return $builder->where('id', '=', $sportID);
        })->inRandomOrder()->first();

        $game = factory(Game::class)->create([
            'week_id' => $week,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'starts_at' => $week->everything_locks_at->addHours(3)
        ]);

        $this->assertEquals($game->id, $homeTeam->getThisWeeksGame()->id);
        $this->assertEquals($game->id, $awayTeam->getThisWeeksGame()->id);

        Week::setTestCurrent(); // clear test week
    }
}
