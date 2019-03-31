<?php

namespace Tests\Feature;

use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Player;
use App\Domain\Models\GamePlayer;
use App\Domain\Models\Position;
use App\Domain\Actions\FillSlot;
use App\Domain\Models\Squad;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroGamePlayerFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @dataProvider provides_a_hero_can_add_a_game_player
     *
     * @param $heroRaceName
     */
    public function a_hero_can_add_a_game_player_for_the_current_week($heroRaceName)
    {

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var \App\Domain\Models\Hero $hero */
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

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/game-player/' . $gamePlayer->uuid);
        $this->assertEquals(201, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertEquals($gamePlayer->id, $hero->gamePlayer->id);
        $this->assertEquals($hero->salary, $gamePlayer->salary);

        Carbon::setTestNow(); // clear testing mock
        Week::setTestCurrent(); // clear test week
    }

    public function provides_a_hero_can_add_a_game_player()
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
     * @dataProvider provides_a_hero_can_add_a_game_player
     *
     * @param $heroRaceName
     */
    public function a_hero_cannot_add_a_game_player_of_the_wrong_position($heroRaceName)
    {
        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();

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
        $positionIDs = $heroRace->positions()->pluck('id')->toArray();
        $position = Position::query()->whereNotIn('id', $positionIDs)->inRandomOrder()->first();

        $gamePlayer->player->positions()->attach($position);

        Week::setTestCurrent($gamePlayer->game->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        Carbon::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/game-player/' . $gamePlayer->uuid);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('position', $response->json()['errors']);

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

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        $squadSalary = 5000;
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create([
            'salary' => $squadSalary
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_race_id' => $heroRace->id,
            'squad_id' => $squad->id
        ]);

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create();

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        /** @var GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create([
            'initial_salary' => $squadSalary - 1000,
            'salary' => $squadSalary + 2000
        ]);

        $gamePlayer->player->positions()->attach($position);

        Week::setTestCurrent($gamePlayer->game->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        Carbon::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/game-player/' . $gamePlayer->uuid);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('salary', $response->json()['errors']);

        $hero = $hero->fresh();
        $this->assertNull($hero->gamePlayer);

        Carbon::setTestNow(); // clear testing mock
        Week::setTestCurrent(); // clear test week
    }


}
