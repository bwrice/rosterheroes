<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\Week;
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


//    /**
//     * @test
//     */
//    public function it_will_create_games_within_range()
//    {
//        /** @var Week $week */
//        $week = factory(Week::class)->create();
//        $gameTime = $week->everything_locks_at;
//        Week::setTestCurrent($week);
//        $gameDTo = new GameDTO();
//    }

    public function it_will_not_create_a_game_within_range()
    {

    }

    public function it_will_remove_a_game_no_longer_in_that_range()
    {

    }
}
