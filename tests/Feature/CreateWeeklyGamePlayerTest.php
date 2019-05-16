<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateWeeklyGamePlayer;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Domain\Models\WeeklyGamePlayer;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateWeeklyGamePlayerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_game_starts_before_the_week_locks()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->subMinutes(15)
        ]);
        $player = factory(Player::class)->create([
            'team_id' => $game->homeTeam->id
        ]);
        $playerGameLogs = collect();
        $action = new CreateWeeklyGamePlayer($week, $game, $player, $playerGameLogs);
        try {

            $action(); //invoke

        } catch (InvalidGameException $exception) {

            $weeklyGamePlayer = WeeklyGamePlayer::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($weeklyGamePlayer);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_player_isnt_a_part_of_the_game()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30)
        ]);
        $player = factory(Player::class)->create(); //random team should be created in factory

        $playerGameLogs = collect();
        $action = new CreateWeeklyGamePlayer($week, $game, $player, $playerGameLogs);
        try {

            $action(); //invoke

        } catch (InvalidPlayerException $exception) {

            $weeklyGamePlayer = WeeklyGamePlayer::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($weeklyGamePlayer);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_player_doesnt_have_any_positions()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30)
        ]);
        /** @var Player $player */
        $player = factory(Player::class)->create([
            'team_id' => $game->awayTeam->id
        ]);

        $this->assertTrue($player->positions->isEmpty());

        $playerGameLogs = collect();
        $action = new CreateWeeklyGamePlayer($week, $game, $player, $playerGameLogs);
        try {

            $action(); //invoke

        } catch (InvalidPlayerException $exception) {

            $weeklyGamePlayer = WeeklyGamePlayer::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($weeklyGamePlayer);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_set_the_salary_the_highest_default_of_the_players_positions()
    {

    }
}
