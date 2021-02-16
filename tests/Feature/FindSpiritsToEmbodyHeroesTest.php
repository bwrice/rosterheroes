<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindSpiritsToEmbodyHeroes;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroRace;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
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
            ->withEssenceCost(7654) // uneven amount because default costs are filtered
            ->forWeek($week)
            ->create();

        $heroB = HeroFactory::new()->heroRace(HeroRace::ORC)->forSquad($npc)->create();
        $position = $heroB->heroRace->positions->random();
        $playerSpiritB = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(7654)
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
            ->withEssenceCost(7654) // uneven amount because default costs are filtered
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
            ->withEssenceCost(7654) // uneven amount because default costs are filtered
            ->forWeek($week)
            ->create();

        $heroB = HeroFactory::new()->forSquad($npc)->create();
        $position = $heroB->heroRace->positions->random();
        $playerSpiritBFactory = PlayerSpiritFactory::new()
            ->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
            ->withEssenceCost(7654)
            ->forWeek($week);
        $playerSpiritB = $playerSpiritBFactory->create();

        // Embody Hero-B
        $heroB->player_spirit_id = $playerSpiritB->id;
        $heroB->save();

        // Create another valid spirit for hero-B
        $playerSpiritBFactory->create();

        $embodyArrays = $this->getDomainAction()->execute($npc);
        $this->assertEquals(1, $embodyArrays->count());
    }

    /**
     * @test
     */
    public function it_will_use_almost_of_a_squads_spirit_essence()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current', 'adventuring-open')->create();
        $npc = SquadFactory::new()->withStartingHeroes()->create();

        $positions = Position::all();
        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($week);

        /*
         * Make a bunch of valid spirits for each hero with varying ranges of essence cost
         */
        $npc->heroes->load('heroRace.positions')->each(function (Hero $hero) use ($playerSpiritFactory, $positions) {
            $position = $positions->filter(function (Position $position) use ($hero) {
                return in_array($position->id, $hero->heroRace->positions->pluck('id')->toArray());
            })->random();
            for ($i = 1; $i <= 10; $i++) {
                $min = 3000 + (($i - 1) * 500);
                $max = 4000 + (($i - 1) * 500);
                $playerSpiritFactory->withPlayerGameLog(PlayerGameLogFactory::new()->withPlayer(PlayerFactory::new()->withPosition($position)))
                    ->withEssenceCost(rand($min, $max))
                    ->create();
            }
        });

        $embodyArrays = $this->getDomainAction()->execute($npc);

        $essenceUsed = $embodyArrays->sum(function ($embodyArray) {
            /** @var PlayerSpirit $spirit */
            $spirit = $embodyArray['player_spirit'];
            return $spirit->essence_cost;
        });

        $this->assertLessThan(1000, $npc->spirit_essence - $essenceUsed);
    }
}
