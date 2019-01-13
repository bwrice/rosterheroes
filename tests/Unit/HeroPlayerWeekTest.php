<?php

namespace Tests\Unit;


use App\Game;
use App\Hero;
use App\HeroRace;
use App\Player;
use App\PlayerWeek;
use App\Position;
use App\Week;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Symfony\Component\Debug\ExceptionHandler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroPlayerWeekTest extends TestCase
{
    /**
     * @test
     * @dataProvider provides_a_hero_will_fail_to_add_a_non_matching_position_player_week
     *
     * @param $heroRaceName
     */
    public function a_hero_will_fail_to_add_a_non_matching_position_player_week($heroRaceName)
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $allowedPositionIDs = $heroRace->positions()->pluck('id')->toArray();

        $playerPosition = Position::query()->whereNotIn('id', $allowedPositionIDs)->inRandomOrder()->first();

        $week = Week::current();

        $game = factory(Game::class)->create([
            'week_id' => $week->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(6)
        ]);

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $player->games()->attach($game);
        $player->positions()->attach($playerPosition);

        /** @var PlayerWeek $playerWeek */
        $playerWeek = factory(PlayerWeek::class)->create([
            'player_id' => $player->id
        ]);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);


        // Mock 6 hours before everything locks
        Carbon::setTestNow($week->everything_locks_at->copy()->subHours(6));

        try {

            $hero->addPlayerWeek($playerWeek);

        } catch ( \Exception $exception ) {

            $this->assertNull($hero->player_week_id);
            $this->assertNull($hero->salary);

            return; // exit
        } finally {

            Carbon::setTestNow(); // clear testing mock
        }

        $this->fail("PlayerWeek with invalid position was added to Hero");
    }

    public function provides_a_hero_will_fail_to_add_a_non_matching_position_player_week()
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
     */
    public function a_hero_will_fail_to_add_a_player_week_with_a_salary_too_high()
    {
        $week = Week::current();

        $game = factory(Game::class)->create([
            'week_id' => $week->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(6)
        ]);

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var Player $player */
        $player = factory(Player::class)->create();
        $player->games()->attach($game);
        $player->positions()->attach($position);

        /** @var PlayerWeek $playerWeek */
        $playerWeek = factory(PlayerWeek::class)->create([
            'player_id' => $player->id,
            'initial_salary' => 11000,
            'salary' => 11000
        ]);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        // Set the squad salary below the player-week salary
        $hero->squad->salary = 9000;
        $hero->squad->save();

        // Mock 6 hours before everything locks
        Carbon::setTestNow($week->everything_locks_at->copy()->subHours(6));

        try {

            $hero->addPlayerWeek($playerWeek);

        } catch ( \Exception $exception ) {

            $this->assertNull($hero->player_week_id);
            $this->assertNull($hero->salary);

            return; // exit
        } finally {

            Carbon::setTestNow(); // clear testing mock
        }

        $this->fail("PlayerWeek with too high of a salary was added to Hero");
    }

    /**
     * @test
     */
    public function a_hero_will_fail_to_add_a_player_that_has_a_game_that_has_started()
    {
        $week = Week::current();

        /** @var Game $game */
        $game = factory(Game::class)->create([
            'week_id' => $week->id,
            'starts_at' => $week->everything_locks_at->copy()->addHours(6)
        ]);

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

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

        // Mock 10 minutes after the game starts
        Carbon::setTestNow($game->starts_at->copy()->addMinutes(10));

        try {

            $hero->addPlayerWeek($playerWeek);

        } catch ( \Exception $exception ) {

            $this->assertNull($hero->player_week_id);
            $this->assertNull($hero->salary);

            return; // exit
        } finally {

            Carbon::setTestNow(); // clear testing mock
        }

        $this->fail("PlayerWeek with a game that is already started was added to Hero");
    }
}
