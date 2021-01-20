<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindSpiritsToEmbodyHeroes;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Week;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindSpiritsToEmbodyHeroesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindSpiritsToEmbodyHeroes
     */
    protected function getDomainAction()
    {
        return app(FindSpiritsToEmbodyHeroes::class);
    }

    /**
     * @test
     */
    public function it_will_return_a_collection_of_embody_hero_arrays()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current', 'adventuring-open')->create();
        $npc = SquadFactory::new()->create();

        // Need different hero races so we can test expected spirits match
        $heroA = HeroFactory::new()->heroRace(HeroRace::DWARF)->forSquad($npc)->create();
        $position = $heroA->heroRace->positions->random();
        $playerSpiritA = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(9876) // uneven amount because default costs are filtered
            ->forWeek($week)
            ->create();

        $heroB = HeroFactory::new()->heroRace(HeroRace::ORC)->forSquad($npc)->create();
        $position = $heroB->heroRace->positions->random();
        $playerSpiritB = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(9876)
            ->forWeek($week)
            ->create();

        $embodyArrays = $this->getDomainAction()->execute($npc);
        $this->assertEquals(2, $embodyArrays->count());

        $embodyArrayA = $embodyArrays->first(function ($embodyArray) use ($heroA) {
            return $embodyArray['hero']->id === $heroA->id;
        });
        $this->assertNotNull($embodyArrayA);
        $this->assertEquals($playerSpiritA->id, $embodyArrayA['player_spirit']->id);

        $embodyArrayB = $embodyArrays->first(function ($embodyArray) use ($heroB) {
            return $embodyArray['hero']->id === $heroB->id;
        });
        $this->assertNotNull($embodyArrayB);
        $this->assertEquals($playerSpiritB->id, $embodyArrayB['player_spirit']->id);
    }

    /**
     * @test
     */
    public function it_will_not_return_duplicate_spirits_to_embody()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current', 'adventuring-open')->create();
        $npc = SquadFactory::new()->create();

        $heroA = HeroFactory::new()->forSquad($npc)->create();
        // Create 2nd hero with same hero-race so they have the same positions for player-spirits
        $heroB = HeroFactory::new()->forSquad($npc)->heroRace($heroA->heroRace->name)->create();
        $position = $heroA->heroRace->positions->random();
        $playerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(9876) // uneven amount because default costs are filtered
            ->forWeek($week)
            ->create();

        $embodyArrays = $this->getDomainAction()->execute($npc);
        $this->assertEquals(1, $embodyArrays->count());
    }

    /**
     * @test
     */
    public function it_will_not_return_heroes_who_are_already_embodied()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current', 'adventuring-open')->create();
        $npc = SquadFactory::new()->create();

        $heroA = HeroFactory::new()->forSquad($npc)->create();
        $position = $heroA->heroRace->positions->random();
        $playerSpiritA = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(9876) // uneven amount because default costs are filtered
            ->forWeek($week)
            ->create();

        $heroB = HeroFactory::new()->forSquad($npc)->create();
        $position = $heroB->heroRace->positions->random();
        $playerSpiritB = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(9876)
            ->forWeek($week)
            ->create();

        // Embody Hero-B
        $heroB->player_spirit_id = $playerSpiritB->id;
        $heroB->save();

        $embodyArrays = $this->getDomainAction()->execute($npc);
        $this->assertEquals(1, $embodyArrays->count());
    }
}
