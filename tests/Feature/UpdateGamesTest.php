<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdateGames;
use Carbon\CarbonImmutable;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateGamesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }


    /**
     * @test
     */
    public function it_will_create_games_within_range()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $gameTime = $week->everything_locks_at->addHours(1);
        Week::setTestCurrent($week);
        $homeTeamOne = factory(Team::class)->create();
        $awayTeamOne = factory(Team::class)->create();
        $externalIDOne = uniqid();
        $gameDTOOne = new GameDTO($gameTime->addHours(1), $homeTeamOne, $awayTeamOne, $externalIDOne);
        $homeTeamTwo = factory(Team::class)->create();
        $awayTeamTwo = factory(Team::class)->create();
        $externalIDTwo = uniqid();
        $gameDTOTwo = new GameDTO($gameTime->addHours(1), $homeTeamTwo, $awayTeamTwo, $externalIDTwo);

        $integration = new MockIntegration(null, null, collect([$gameDTOOne, $gameDTOTwo]));
        app()->instance(StatsIntegration::class, $integration);

        UpdateGames::dispatchNow();
    }

    public function it_will_not_create_a_game_within_range()
    {

    }

    public function it_will_remove_a_game_no_longer_in_that_range()
    {

    }
}
