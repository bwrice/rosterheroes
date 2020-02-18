<?php

namespace Tests\Feature;

use App\Domain\Collections\CombatHeroCollection;
use App\Domain\Combat\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Factories\Combat\CombatHeroFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombatHeroCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function combat_heroes_will_inherit_combat_positions_correctly()
    {
        $factory = CombatHeroFactory::new();
        $frontLineFactory = $factory->withCombatPosition(CombatPosition::FRONT_LINE);
        $frontLineHero = $frontLineFactory->create();
        $frontLineHero2 = $frontLineFactory->create();
        $highGroundHero = $factory->withCombatPosition(CombatPosition::HIGH_GROUND)->create();
        $collection = new CombatHeroCollection([
            $frontLineHero,
            $frontLineHero2,
            $highGroundHero
        ]);
        $this->assertEquals(1, $frontLineHero->allCombatPositions()->count());
        $this->assertEquals(1, $frontLineHero2->allCombatPositions()->count());
        $this->assertEquals(1, $highGroundHero->allCombatPositions()->count());

        $collection->updateCombatPositions(CombatPosition::all());

        foreach([
            $frontLineHero,
            $frontLineHero2
                ] as $combatHero) {
            /** @var CombatHero $combatHero */
            $combatHeroAllCombatPositions = $combatHero->allCombatPositions();
            $count = $combatHeroAllCombatPositions->count();
            $this->assertEquals(2,  $count);
            $backLine = $combatHeroAllCombatPositions->first(function (CombatPosition $combatPosition) {
                return $combatPosition->name === CombatPosition::BACK_LINE;
            });
            $this->assertNotNull($backLine);
        }
        $this->assertEquals(1, $highGroundHero->allCombatPositions()->count());
    }
}
