<?php

namespace Tests\Unit;

use App\Game;
use App\Player;
use App\Week;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_return_this_weeks_game()
    {
        $week = Week::current();

        $game = factory(Game::class)->create([
            'week_id' => $week->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(6)
        ]);

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $player->games()->attach($game);

        $this->assertEquals($week->id, $player->getThisWeeksGame()->id);
    }
}
