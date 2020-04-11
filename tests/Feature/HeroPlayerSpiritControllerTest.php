<?php

namespace Tests\Feature;

use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Actions\FillSlotsWithItemAction;
use App\Domain\Models\Squad;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroPlayerSpiritControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @dataProvider provides_a_hero_can_add_a_player_spirit
     *
     * @param $heroRaceName
     */
    public function a_hero_can_add_a_player_spirit_for_the_current_week($heroRaceName)
    {
        $this->withoutExceptionHandling();

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::where('name', '=', $heroRaceName)->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->state('with-measurables')->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();

        $playerSpirit->playerGameLog->player->positions()->attach($position);

        CurrentWeek::setTestCurrent($playerSpirit->week);

        // Mock 6 hours before everything locks
        Date::setTestNow(Week::current()->adventuring_locks_at->subHours(6));
        // Set game time
        $game = $playerSpirit->playerGameLog->game;
        $game->starts_at = Week::current()->adventuring_locks_at->addHours(2);
        $game->save();

        Passport::actingAs($hero->squad->user);

        $response = $this->json('POST', 'api/v1/heroes/'. $hero->slug . '/player-spirit', [
            'spirit' => $playerSpirit->uuid
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertEquals($playerSpirit->id, $hero->playerSpirit->id);
    }

    public function provides_a_hero_can_add_a_player_spirit()
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
    public function it_will_add_a_spirit_if_the_hero_essence_used_plus_squad_available_essence_is_enough()
    {
        $squadSpiritEssence = 10000;
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create([
            'spirit_essence' => $squadSpiritEssence
        ]);

        $alreadyFilledPlayerSpiritCost = 4000;
        $alreadyFilledPlayerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => $alreadyFilledPlayerSpiritCost
        ]);

        $alreadyFilledHero = factory(Hero::class)->create([
            'player_spirit_id' => $alreadyFilledPlayerSpirit->id,
            'squad_id' => $squad->id
        ]);

        $this->assertEquals($squadSpiritEssence - $alreadyFilledPlayerSpiritCost, $squad->availableSpiritEssence());

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->state('with-measurables')->create([
            'hero_race_id' => $heroRace->id,
            'squad_id' => $squad->id
        ]);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => ($squadSpiritEssence - $alreadyFilledPlayerSpiritCost) - 1500
        ]);

        $playerSpirit->playerGameLog->player->positions()->attach($position);

        CurrentWeek::setTestCurrent($playerSpirit->week);

        // Mock 6 hours before everything locks
        Date::setTestNow(Week::current()->adventuring_locks_at->subHours(6));
        // Set game time
        $game = $playerSpirit->playerGameLog->game;
        $game->starts_at = Week::current()->adventuring_locks_at->addHours(2);
        $game->save();

        Passport::actingAs($hero->squad->user);

        $response = $this->json('POST', 'api/v1/heroes/'. $hero->slug . '/player-spirit', [
            'spirit' => $playerSpirit->uuid
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertEquals($hero->playerSpirit->id, $playerSpirit->id);
    }

    /**
     * @test
     */
    public function it_will_remove_a_player_spirit()
    {
        $this->withoutExceptionHandling();
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->state('with-measurables')->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        CurrentWeek::setTestCurrent($playerSpirit->week);

        Passport::actingAs($hero->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->adventuring_locks_at->copy()->subHours(6));

        $response = $this->json('DELETE', 'api/v1/heroes/'. $hero->slug . '/player-spirit/' . $playerSpirit->uuid);

        $this->assertEquals(200, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertNull($hero->playerSpirit);
    }

    public function it_will_throw_a_validation_exception_if_a_hero_spirit_exception_is_thrown()
    {
        // TODO
    }
}
