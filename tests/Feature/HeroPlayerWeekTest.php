<?php

namespace Tests\Feature;

use App\Game;
use App\Hero;
use App\Heroes\HeroPosts\HeroPost;
use App\HeroRace;
use App\Player;
use App\GamePlayer;
use App\Positions\Position;
use App\Squad;
use App\Team;
use App\Weeks\Week;
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

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        /** @var GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create();

        $gamePlayer->player->positions()->attach($position);

        Week::setTestCurrent($gamePlayer->game->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        Carbon::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/player-week/' . $gamePlayer->uuid);
        $this->assertEquals(201, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertEquals($gamePlayer->id, $hero->gamePlayer->id);
        $this->assertEquals($hero->salary, $gamePlayer->salary);

        Carbon::setTestNow(); // clear testing mock
        Week::setTestCurrent(); // clear test week
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

    /**
     * @test
     * @dataProvider provides_a_hero_can_add_a_player_week
     *
     * @param $heroRaceName
     */
    public function a_hero_cannot_add_a_player_week_of_the_wrong_position($heroRaceName)
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $heroRacePositionIDs = $heroRace->positions()->pluck('id')->toArray();
        $playerPosition = Position::query()->whereNotIn('id', $heroRacePositionIDs)->inRandomOrder()->first();

        /** @var \App\Weeks\Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $player->positions()->attach($playerPosition);

        /** @var GamePlayer $playerWeek */
        $playerWeek = factory(GamePlayer::class)->create([
            'player_id' => $player->id
        ]);

        /** @var Team $homeTeam */
        $homeTeam = $player->team;
        $sportID = $homeTeam->sport->id;

        /** @var Team $awayTeam */
        $awayTeam = Team::query()->whereHas('sport', function(Builder $builder) use ($sportID) {
            return $builder->where('id', '=', $sportID);
        })->inRandomOrder()->first();

        $game = factory(Game::class)->create([
            'week_id' => $week,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(3)
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_race_id' => $heroRace->id
        ]);
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        Carbon::setTestNow($week->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/player-week/' . $playerWeek->uuid);
        $this->assertEquals(422, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertNull($hero->gamePlayer);

        Carbon::setTestNow(); // clear testing mock
        Week::setTestCurrent(); // clear test week
    }

    /**
     * @test
     */
    public function a_hero_cannot_add_a_player_with_too_high_a_salary()
    {

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var \App\Weeks\Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);

        /** @var Player $player */
        $player = factory(Player::class)->create();

        $player->positions()->attach($position);

        $playerSalary = 8000;
        /** @var GamePlayer $playerWeek */
        $playerWeek = factory(GamePlayer::class)->create([
            'player_id' => $player->id,
            'initial_salary' => $playerSalary,
            'salary' => $playerSalary
        ]);

        /** @var Team $homeTeam */
        $homeTeam = $player->team;
        $sportID = $homeTeam->sport->id;

        /** @var Team $awayTeam */
        $awayTeam = Team::query()->whereHas('sport', function(Builder $builder) use ($sportID) {
            return $builder->where('id', '=', $sportID);
        })->inRandomOrder()->first();

        $game = factory(Game::class)->create([
            'week_id' => $week,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(3)
        ]);

        $squad = factory(Squad::class)->create([
            'salary' => $playerSalary - 1000
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_race_id' => $heroRace->id,
            'squad_id' => $squad->id,
        ]);
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        Carbon::setTestNow($week->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/player-week/' . $playerWeek->uuid);
        $this->assertEquals(422, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertNull($hero->gamePlayer);

        Carbon::setTestNow(); // clear testing mock
        Week::setTestCurrent(); // clear test week
    }


}
