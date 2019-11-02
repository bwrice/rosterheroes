<?php

namespace Tests\Unit;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\PlayerSpirit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_the_correct_essence_available_when_other_heroes_have_spirits()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        $squad = $hero->squad;
        $squad->spirit_essence = 30000;
        $squad->save();

        $otherHeroSpiritEssenceUsed = 6000;
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => $otherHeroSpiritEssenceUsed
        ]);

        /** @var Hero $otherHero */
        $otherHero = factory(Hero::class)->create([
            'player_spirit_id' => $playerSpirit->id,
            'squad_id' => $squad->id
        ]);

        $this->assertEquals($squad->spirit_essence - $otherHeroSpiritEssenceUsed, $hero->availableEssence());
    }

    /**
     * @test
     */
    public function it_will_return_the_correct_essence_available_when_it_has_a_spirit_already()
    {
        $otherHeroSpiritEssenceUsed = 6000;
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'essence_cost' => $otherHeroSpiritEssenceUsed
        ]);

        /** @var Hero $hero */
        $hero = factory(Hero::class)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $squad = $hero->squad;
        $squad->spirit_essence = 30000;
        $squad->save();

        $this->assertEquals($squad->spirit_essence, $hero->availableEssence());
    }
}
