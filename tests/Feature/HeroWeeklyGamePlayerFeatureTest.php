<?php

namespace Tests\Feature;

use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Player;
use App\Domain\Models\WeeklyGamePlayer;
use App\Domain\Models\Position;
use App\Domain\Actions\FillSlot;
use App\Domain\Models\Squad;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroWeeklyGamePlayerFeatureTest extends TestCase
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
        $this->withoutExceptionHandling();

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id
        ]);

        /** @var WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create();

        $weeklyGamePlayer->gamePlayer->player->positions()->attach($position);

        Week::setTestCurrent($weeklyGamePlayer->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/weekly-game-player/' . $weeklyGamePlayer->uuid);
        $this->assertEquals(201, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertEquals($weeklyGamePlayer->id, $hero->weeklyGamePlayer->id);

        CarbonImmutable::setTestNow(); // clear testing mock
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

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id
        ]);

        /** @var WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create();
        $positionIDs = $heroRace->positions()->pluck('id')->toArray();
        $position = Position::query()->whereNotIn('id', $positionIDs)->inRandomOrder()->first();

        $weeklyGamePlayer->gamePlayer->player->positions()->attach($position);

        Week::setTestCurrent($weeklyGamePlayer->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/weekly-game-player/' . $weeklyGamePlayer->uuid);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('position', $response->json()['errors']);

        $hero = $hero->fresh();
        $this->assertNull($hero->weeklyGamePlayer);

        CarbonImmutable::setTestNow(); // clear testing mock
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


        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        $squadSalary = 5000;
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create([
            'salary' => $squadSalary
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
            'squad_id' => $squad->id
        ]);

        /** @var WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create([
            'salary' => $squadSalary + 2000
        ]);

        $weeklyGamePlayer->gamePlayer->player->positions()->attach($position);

        Week::setTestCurrent($weeklyGamePlayer->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/hero/'. $hero->uuid . '/weekly-game-player/' . $weeklyGamePlayer->uuid);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('salary', $response->json()['errors']);

        $hero = $hero->fresh();
        $this->assertNull($hero->weeklyGamePlayer);

        CarbonImmutable::setTestNow(); // clear testing mock
        Week::setTestCurrent(); // clear test week
    }


}
