<?php

namespace Tests\Feature;

use App\Domain\Combat\Combatants\CombatHeroDataMapper;
use App\Domain\Combat\Combatants\CombatMinionDataMapper;
use App\Domain\Models\CombatPosition;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\MinionCombatAttackFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombatMinionDataMapperTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_map_the_data_array_into_a_combat_minion()
    {
        $original = CombatMinionFactory::new()->create();

        /** @var CombatMinionDataMapper $mapper */
        $mapper = app(CombatMinionDataMapper::class);
        $combatMinion = $mapper->getCombatMinion($original->toArray());
        $this->assertTrue($original == $combatMinion);
    }

    /**
     * @test
     */
    public function it_will_map_to_matching_combat_minion_after_receiving_damage()
    {
        $original = CombatMinionFactory::new()->create();
        $original->receiveDamage(10);

        /** @var CombatMinionDataMapper $mapper */
        $mapper = app(CombatMinionDataMapper::class);
        $combatMinion = $mapper->getCombatMinion($original->toArray());
        $this->assertTrue($original == $combatMinion);
    }

    /**
     * @test
     */
    public function it_will_map_to_matching_combat_minion_if_inherited_combat_positions_changed()
    {
        $original = CombatMinionFactory::new()->create();
        $initialCombatPosition = $original->getInitialCombatPosition();
        $inheritedPositions = CombatPosition::query()
            ->where('id', '!=', $initialCombatPosition->id)
            ->take(2)->get();
        $original->setInheritedCombatPositions($inheritedPositions->values());


        /** @var CombatMinionDataMapper $mapper */
        $mapper = app(CombatMinionDataMapper::class);
        $combatMinion = $mapper->getCombatMinion($original->toArray());
        $this->assertTrue($original == $combatMinion);
    }

    /**
     * @test
     */
    public function it_will_correctly_map_minion_combat_attacks()
    {
        $minionCombatAttackFactory = new MinionCombatAttackFactory();
        $original = CombatMinionFactory::new()->withMinionCombatAttacks(collect([$minionCombatAttackFactory]))->create();
        $initialCombatPosition = $original->getInitialCombatPosition();
        $inheritedPositions = CombatPosition::query()
            ->where('id', '!=', $initialCombatPosition->id)
            ->take(2)->get();
        $original->setInheritedCombatPositions($inheritedPositions->values());


        /** @var CombatMinionDataMapper $mapper */
        $mapper = app(CombatMinionDataMapper::class);
        $combatMinion = $mapper->getCombatMinion($original->toArray());
        $this->assertTrue($original == $combatMinion);
    }
}
