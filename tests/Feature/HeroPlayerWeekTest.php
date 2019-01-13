<?php

namespace Tests\Feature;

use App\Game;
use App\Hero;
use App\HeroRace;
use App\Player;
use App\PlayerWeek;
use App\Week;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroPlayerWeekTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @dataProvider provides_a_hero_can_add_a_player_week
     *
     * @param $heroRaceName
     */
    public function a_hero_can_add_a_player_week($heroRaceName)
    {
        $this->withoutExceptionHandling();

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        $week = Week::current();

        $game = factory(Game::class)->create([
            'week_id' => $week->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(6)
        ]);

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $player->games()->attach($game);
        $player->positions()->attach($position);

        /** @var PlayerWeek $playerWeek */
        $playerWeek = factory(PlayerWeek::class)->create([
            'player_id' => $player->id
        ]);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        Passport::actingAs($hero->squad->user);

        // Mock 6 hours before everything locks
        Carbon::setTestNow($week->everything_locks_at->copy()->subHours(6));

        $response = $this->post('api/hero/'. $hero->uuid . '/player-week/' . $playerWeek->uuid);
        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals($playerWeek->id, $hero->fresh()->playerWeek->id);

        Carbon::setTestNow(); // clear testing mock
    }

    public function provides_a_hero_can_add_a_player_week()
    {
        return [
           HeroRace::HUMAN =>  [
                'herRaceName' => HeroRace::HUMAN
            ],
            HeroRace::ORC =>  [
                'herRaceName' => HeroRace::ORC
            ],
            HeroRace::ELF =>  [
                'herRaceName' => HeroRace::ELF
            ],
            HeroRace::DWARF =>  [
                'herRaceName' => HeroRace::DWARF
            ],
        ];
    }

}
