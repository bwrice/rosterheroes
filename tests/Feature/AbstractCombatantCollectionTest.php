<?php

namespace Tests\Feature;

use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Factories\Combat\AbstractCombatantFactory;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function foo\func;

class AbstractCombatantCollectionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provides_abstract_combatants_will_inherit_combat_positions_correctly
     * @param string $factoryClass
     */
    public function abstract_combatants_will_inherit_combat_positions_with_no_combatants(string $factoryClass)
    {
        /** @var AbstractCombatantFactory $factory */
        $factory = call_user_func($factoryClass . '::new');
        $frontLineFactory = $factory->withCombatPosition(CombatPosition::FRONT_LINE);
        $frontLineCombatant = $frontLineFactory->create();
        $frontLineCombatant2 = $frontLineFactory->create();
        $highGroundCombatant = $factory->withCombatPosition(CombatPosition::HIGH_GROUND)->create();
        $collection = new AbstractCombatantCollection([
            $frontLineCombatant,
            $frontLineCombatant2,
            $highGroundCombatant
        ]);
        $this->assertEquals(1, $frontLineCombatant->allCombatPositions()->count());
        $this->assertEquals(1, $frontLineCombatant2->allCombatPositions()->count());
        $this->assertEquals(1, $highGroundCombatant->allCombatPositions()->count());

        $collection->updateCombatPositions(CombatPosition::all());

        foreach([
            $frontLineCombatant,
            $frontLineCombatant2
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
        $this->assertEquals(1, $highGroundCombatant->allCombatPositions()->count());
    }
    /**
     * @test
     * @dataProvider provides_abstract_combatants_will_inherit_combat_positions_correctly
     * @param string $factoryClass
     */
    public function abstract_combatants_will_inherit_combat_positions_with_no_survivors(string $factoryClass)
    {
        /** @var AbstractCombatantFactory $factory */
        $factory = call_user_func($factoryClass . '::new');
        $frontLineCombatant = $factory->withCombatPosition(CombatPosition::FRONT_LINE)->create();
        $backLineCombatant = $factory->withCombatPosition(CombatPosition::BACK_LINE)->create();
        $highGroundCombatant = $factory->withCombatPosition(CombatPosition::HIGH_GROUND)->create();
        $collection = new AbstractCombatantCollection([
            $frontLineCombatant,
            $backLineCombatant,
            $highGroundCombatant
        ]);
        $this->assertEquals(1, $frontLineCombatant->allCombatPositions()->count());
        $this->assertEquals(1, $backLineCombatant->allCombatPositions()->count());
        $this->assertEquals(1, $highGroundCombatant->allCombatPositions()->count());

        /** @var CombatPositionCollection $combatPositions */
        $combatPositions = CombatPosition::all();
        $collection->updateCombatPositions($combatPositions);

        $this->assertEquals(1, $frontLineCombatant->allCombatPositions()->count());
        $this->assertEquals(1, $backLineCombatant->allCombatPositions()->count());
        $this->assertEquals(1, $highGroundCombatant->allCombatPositions()->count());

        $frontLineCombatant->setCurrentHealth(0);
        $backLineCombatant->setCurrentHealth(0);

        $collection->updateCombatPositions($combatPositions);
        $this->assertEquals(3, $highGroundCombatant->allCombatPositions()->count());
        $combatPositions->each(function (CombatPosition $combatPositionToMatch) use ($highGroundCombatant) {
            $match = $highGroundCombatant->allCombatPositions()->first(function (CombatPosition $combatPosition) use ($combatPositionToMatch) {
                return $combatPosition->id === $combatPositionToMatch->id;
            });
            $this->assertNotNull($match);
        });
    }

    public function provides_abstract_combatants_will_inherit_combat_positions_correctly()
    {
        return [
            'combat-heroes' => [
                'factoryClass' => CombatHeroFactory::class
            ],
            'combat-minions' => [
                'factoryClass' => CombatMinionFactory::class
            ]
        ];
    }

}
