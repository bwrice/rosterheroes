<?php

namespace Tests\Unit;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\HeroPlayerSpiritException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddSpiritToHeroActionTest extends TestCase
{
    public function a_hero_cannot_add_spirt_for_a_non_current_week()
    {
        // TODO
    }

    /**
     * @test
     */
    public function a_hero_cannot_add_a_player_spirit_of_the_wrong_position()
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();

        /** @var Hero $hero */
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
        // Mock 6 hours before everything locks
        Date::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        try {

            $action = new AddSpiritToHeroAction($hero, $playerSpirit);
            $action();

        } catch (HeroPlayerSpiritException $exception) {

            $this->assertEquals(HeroPlayerSpiritException::INVALID_PLAYER_POSITIONS, $exception->getCode());

            $hero = $hero->fresh();
            $this->assertNull($hero->playerSpirit);

            return;
        }
    }

    /**
     * @test
     */
    public function a_hero_cannot_add_a_player_with_too_much_essence_cost()
    {
        $squadSpiritEssence = 10000;
        /** @var Squad $squad */
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

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();
        $position = $heroRace->positions()->inRandomOrder()->first();

        /** @var Hero $hero */
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
            'essence_cost' => ($squadSpiritEssence - $alreadyFilledPlayerSpiritCost) + 2000 // essence cost too much
        ]);

        $playerSpirit->player->positions()->attach($position);

        Week::setTestCurrent($playerSpirit->week);
        // Mock 6 hours before everything locks
        Date::setTestNow(Week::current()->everything_locks_at->copy()->subHours(6));

        try {

            $action = new AddSpiritToHeroAction($hero, $playerSpirit);
            $action();

        } catch (HeroPlayerSpiritException $exception) {

            $hero = $hero->fresh();
            $this->assertNull($hero->playerSpirit);

            $this->assertEquals(HeroPlayerSpiritException::NOT_ENOUGH_ESSENCE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    public function a_hero_cannot_add_a_player_spirit_whos_game_has_started()
    {
        // TODO
    }

    public function a_hero_cannot_remove_a_player_spirit_whos_game_has_started()
    {
        // TODO
    }

    public function a_hero_cannot_add_a_spirit_to_replace_a_spirit_whos_game_has_started()
    {
        // TODO
    }

    public function a_hero_cannot_add_a_spirit_that_is_attached_not_another_hero_for_the_squad()
    {
        // TODO
    }


}
