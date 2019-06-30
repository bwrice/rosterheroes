<?php

namespace Tests\Feature;

use App\Domain\Models\Player;
use App\Domain\Models\Position;
use App\Domain\Models\Sport;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeekPlayerSpiritControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_return_player_spirits_for_a_given_week()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        // will have different week
        $filteredOut = factory(PlayerSpirit::class)->create();

        $baseURI = '/api/week/' . $week->uuid . '/player-spirits';

        $response = $this->get($baseURI);
        $response
            ->assertStatus(200)
            ->assertJson([
            'data' => [
                [
                    'uuid' => $playerSpirit->uuid,
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
    
    /**
     * @test
     */
    public function it_will_filter_by_position()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $position = Position::query()->where('name', '=', Position::OUTFIELD)->first();

        $player->positions()->save($position);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'player_id' => $player->id
        ]);

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $position = Position::query()->where('name', '=', Position::PITCHER)->first();

        $player->positions()->save($position);

        /** @var PlayerSpirit $filteredOut */
        $filteredOut = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'player_id' => $player->id
        ]);

        $uri = '/api/week/' . $week->uuid . '/player-spirits';
        $uri .= '?filter[position]=outfield,running-back';

        $response = $this->get($uri);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'uuid' => $playerSpirit->uuid,
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

    /**
     * @test
     */
    public function it_will_filter_by_minimum_salary()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'salary' => 5000
        ]);

        /** @var PlayerSpirit $filteredOut */
        $filteredOut = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'salary' => 4999
        ]);

        $uri = '/api/week/' . $week->uuid . '/player-spirits';
        $uri .= '?filter[min-salary]=5000';

        $response = $this->get($uri);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'uuid' => $playerSpirit->uuid,
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

    /**
     * @test
     */
    public function it_will_filter_by_maximum_salary()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'salary' => 5000
        ]);

        /** @var PlayerSpirit $filteredOut */
        $filteredOut = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'salary' => 5001
        ]);

        $uri = '/api/week/' . $week->uuid . '/player-spirits';
        $uri .= '?filter[max-salary]=5000';

        $response = $this->get($uri);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'uuid' => $playerSpirit->uuid,
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
