<?php

namespace Tests\Feature;

use App\Game;
use App\Hero;
use App\Player;
use App\PlayerWeek;
use App\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroPlayerWeekTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_hero_can_add_a_player_week()
    {
        $this->withoutExceptionHandling();

        $week = Week::current();
        $game = factory(Game::class)->create([
            'week_id' => $week->id
        ]);
        /** @var Player $player */
        $player = factory(Player::class)->create();
        $player->games()->attach($game);

        /** @var PlayerWeek $playerWeek */
        $playerWeek = factory(PlayerWeek::class)->create([
            'player_id' => $player->id
        ]);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        Passport::actingAs($hero->squad->user);

        $response = $this->post('api/hero/'. $hero->uuid . '/player-week/' . $playerWeek->uuid);
        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals($playerWeek->id, $hero->fresh()->playerWeek->id);
    }

}
