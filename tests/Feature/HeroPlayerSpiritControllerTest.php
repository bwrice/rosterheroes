<?php

namespace Tests\Feature;

use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Actions\FillSlotAction;
use App\Domain\Models\Squad;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
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
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id
        ]);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();

        $playerSpirit->player->positions()->attach($position);

        Week::setTestCurrent($playerSpirit->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        Date::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/v1/heroes/'. $hero->uuid . '/player-spirit/' . $playerSpirit->uuid);
        $this->assertEquals(201, $response->getStatusCode());

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
     * @dataProvider provides_a_hero_can_add_a_player_spirit
     *
     * @param $heroRaceName
     */
    public function a_hero_cannot_add_a_player_spirit_of_the_wrong_position($heroRaceName)
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

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();
        $positionIDs = $heroRace->positions()->pluck('id')->toArray();
        $position = Position::query()->whereNotIn('id', $positionIDs)->inRandomOrder()->first();

        $playerSpirit->player->positions()->attach($position);

        Week::setTestCurrent($playerSpirit->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/v1/heroes/'. $hero->uuid . '/player-spirit/' . $playerSpirit->uuid);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('position', $response->json()['errors']);

        $hero = $hero->fresh();
        $this->assertNull($hero->playerSpirit);
    }

    public function it_will_not_add_a_player_spirit_whos_game_has_started()
    {
        // TODO
    }

    /**
     * @test
     */
    public function a_hero_cannot_add_a_player_with_too_much_essence_cost()
    {
        $squadSpiritEssence = 10000;
        /** @var \App\Domain\Models\Squad $squad */
        $squad = factory(Squad::class)->create([
            'spirit_essence' => $squadSpiritEssence
        ]);

        $alreadyFilledPlayerSpiritCost = 6000;
        $alreadyFilledPlayerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => $alreadyFilledPlayerSpiritCost
        ]);

        $alreadyFilledHero = factory(Hero::class)->create([
            'player_spirit_id' => $alreadyFilledPlayerSpirit->id
        ]);

        $alreadyFilledHeroPost = factory(HeroPost::class)->create([
            'hero_id' => $alreadyFilledHero->id,
            'squad_id' => $squad->id
        ]);

        $this->assertEquals($squadSpiritEssence - $alreadyFilledPlayerSpiritCost, $squad->availableSpiritEssence());

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
            'squad_id' => $squad->id
        ]);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => ($squadSpiritEssence - $alreadyFilledPlayerSpiritCost) + 2000
        ]);

        $playerSpirit->player->positions()->attach($position);

        Week::setTestCurrent($playerSpirit->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/v1/heroes/'. $hero->uuid . '/player-spirit/' . $playerSpirit->uuid);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('essence', $response->json()['errors']);

        $hero = $hero->fresh();
        $this->assertNull($hero->playerSpirit);
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
            'player_spirit_id' => $alreadyFilledPlayerSpirit->id
        ]);

        $alreadyFilledHeroPost = factory(HeroPost::class)->create([
            'hero_id' => $alreadyFilledHero->id,
            'squad_id' => $squad->id
        ]);

        $this->assertEquals($squadSpiritEssence - $alreadyFilledPlayerSpiritCost, $squad->availableSpiritEssence());

        /** @var \App\Domain\Models\HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
            'squad_id' => $squad->id
        ]);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => ($squadSpiritEssence - $alreadyFilledPlayerSpiritCost) - 1500
        ]);

        $playerSpirit->player->positions()->attach($position);

        Week::setTestCurrent($playerSpirit->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('POST', 'api/v1/heroes/'. $hero->uuid . '/player-spirit/' . $playerSpirit->uuid);
        $this->assertEquals(201, $response->getStatusCode());

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
        $hero = factory(Hero::class)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        /** @var \App\Domain\Models\Hero $hero */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id
        ]);

        Week::setTestCurrent($playerSpirit->week);

        Passport::actingAs($heroPost->squad->user);

        // Mock 6 hours before everything locks
        CarbonImmutable::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        $response = $this->json('DELETE', 'api/v1/heroes/'. $hero->uuid . '/player-spirit/' . $playerSpirit->uuid);
        $this->assertEquals(200, $response->getStatusCode());

        $hero = $hero->fresh();
        $this->assertNull($hero->playerSpirit);
    }

    public function it_will_not_remove_a_player_spirit_whos_game_has_started()
    {
        // TODO
    }

    public function it_will_not_add_a_spirit_to_replace_a_spirit_whos_game_has_started()
    {
        // TODO
    }

    public function it_will_not_add_a_spirit_that_is_attached_not_another_hero_for_the_squad()
    {
        // TODO
    }

}
