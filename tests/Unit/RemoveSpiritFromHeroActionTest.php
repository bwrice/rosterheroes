<?php

namespace Tests\Unit;

use App\Domain\Actions\RemoveSpiritFromHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\HeroPlayerSpiritException;
use Cake\Chronos\Date;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveSpiritFromHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function removing_a_hero_whos_game_has_started_will_throw_an_exception()
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();

        $game = $playerSpirit->playerGameLog->game;

        // set current week to spirit's week
        Week::setTestCurrent($playerSpirit->week);

        // set game time start to past
        $game->starts_at = Date::now()->subHour();
        $game->save();

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        /** @var RemoveSpiritFromHeroAction $removeAction */
        $removeAction = app(RemoveSpiritFromHeroAction::class);

        try {
            $playerSpirit = $playerSpirit->fresh();
            $removeAction->execute($hero, $playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $this->assertEquals(HeroPlayerSpiritException::GAME_STARTED, $exception->getCode());
            $this->assertNotNull($hero->fresh()->playerSpirit);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function removing_a_spirit_not_embodying_a_hero_will_throw_an_exception()
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create();
        // set current week to spirit's week
        Week::setTestCurrent($playerSpirit->week);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        /** @var RemoveSpiritFromHeroAction $removeAction */
        $removeAction = app(RemoveSpiritFromHeroAction::class);

        /** @var PlayerSpirit $playerSpirit */
        $wrongPlayerSpirit = factory(PlayerSpirit::class)->create();

        try {
            $playerSpirit = $playerSpirit->fresh();
            $removeAction->execute($hero, $wrongPlayerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $this->assertEquals(HeroPlayerSpiritException::NOT_EMBODIED_BY, $exception->getCode());
            $this->assertNotNull($hero->fresh()->playerSpirit);

            return;
        }

        $this->fail("Exception not thrown");
    }


}
