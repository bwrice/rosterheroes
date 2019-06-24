<?php

namespace Tests\Feature;

use App\Domain\Models\Player;
use App\Domain\Models\Position;
use App\Domain\Models\Sport;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use App\Domain\Models\WeeklyGamePlayer;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeeklyGamePlayerControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_return_weekly_game_players_for_the_current_week()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create([
            'week_id' => $week->id
        ]);

        // will have different week
        $wrongWeeklyGamePlayer = factory(WeeklyGamePlayer::class)->create();

        $response = $this->get('/api/weekly-game-players');
        $response
            ->assertStatus(200)
            ->assertJson([
            'data' => [
                [
                    'uuid' => $weeklyGamePlayer->uuid,
                    'player' => [
                        'team' => [],
                        'positions' => []
                    ],
                    'game' => [
                        'homeTeam' => [],
                        'awayTeam' => []
                    ]
                ]
            ]
        ]);

        $this->assertEquals(1, count($response->json()['data']));
    }
}
