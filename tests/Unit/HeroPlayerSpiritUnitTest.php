<?php

namespace Tests\Unit;

use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\NotEnoughEssenceException;
use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Domain\Models\HeroPostType;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HeroPlayerSpiritUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function adding_a_game_player_without_a_valid_position_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id
        ]);

        /** @var \App\Domain\Models\PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();
        Week::setTestCurrent($playerSpirit->week);

        $validPositions = $hero->heroRace->positions;

        // where NOT IN
        $playerPosition = Position::query()
            ->whereNotIn('id', $validPositions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $playerSpirit->player->positions()->attach($playerPosition);

        try {

            $hero->addPlayerSpirit($playerSpirit);

        } catch (InvalidPositionsException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->playerSpirit);

            Week::setTestCurrent(); //clear testing week
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_game_player_with_too_high_an_essence_cost_will_throw_an_exception()
    {

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create();

        $squadSpiritEssence = 5000;
        $squad = factory(Squad::class)->create([
            'spirit_essence' => $squadSpiritEssence
        ]);

        /** @var \App\Domain\Models\HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
            'squad_id' => $squad->id,
        ]);

        $essenceCost = $squadSpiritEssence + 3000;
        /** @var \App\Domain\Models\PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => $essenceCost
        ]);

        Week::setTestCurrent($playerSpirit->week);

        /*
         * We don't get positions from the hero post type, but instead, get them from the
         * HeroRace directly. It's not this test's responsibility to check the hero's race is valid for the hero post
         */

        $validPositions = $hero->heroRace->positions;
        $validPositionIDs = $validPositions->pluck('id')->toArray();
        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $validPositionIDs)
            ->inRandomOrder()->first();

        $this->assertTrue(in_array($playerPosition->id, $validPositionIDs), 'Position ID');
        $playerSpirit->player->positions()->attach($playerPosition);

        $this->assertEquals($squadSpiritEssence, $hero->availableEssence());

        try {

            $hero->addPlayerSpirit($playerSpirit);

        } catch (NotEnoughEssenceException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->playerSpirit);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_game_player_with_a_game_that_has_passed_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        /** @var \App\Domain\Models\HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
        ]);

        /** @var \App\Domain\Models\PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();

        Week::setTestCurrent($playerSpirit->week);
        CarbonImmutable::setTestNow($playerSpirit->game->starts_at->copy()->addMinutes(5));

        $validPositions = $hero->heroRace->positions;

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $validPositions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $playerSpirit->player->positions()->attach($playerPosition);

        try {

            $hero->addPlayerSpirit($playerSpirit);

        } catch (GameStartedException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->playerSpirit);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_player_game_for_a_different_week_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
        ]);

        /** @var \App\Domain\Models\PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();

        /** @var \App\Domain\Models\Week $differentWeek */
        $differentWeek = factory(Week::class)->create();

        Week::setTestCurrent($differentWeek);
        CarbonImmutable::setTestNow($differentWeek->ends_at->copy()->subDays(2));

        $validPositions = $hero->heroRace->positions;

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $validPositions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $playerSpirit->player->positions()->attach($playerPosition);

        try {

            $hero->addPlayerSpirit($playerSpirit);

        } catch (InvalidWeekException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->playerSpirit);
            $this->assertEquals($e->getWeek(), $playerSpirit->week);

            return;
        }

        $this->fail("Exception not thrown");
    }
}
