<?php

namespace Tests\Feature;

use App\Domain\Combat\Combatants\CombatHeroDataMapper;
use App\Domain\Models\CombatPosition;
use App\Factories\Combat\CombatHeroFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombatHeroDataMapperTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_map_the_data_array_into_a_combat_hero()
    {
        $original = CombatHeroFactory::new()->create();

        /** @var CombatHeroDataMapper $mapper */
        $mapper = app(CombatHeroDataMapper::class);
        $combatHero = $mapper->getCombatHero($original->toArray());
        $this->assertTrue($original == $combatHero);
    }

    /**
     * @test
     */
    public function it_will_map_to_matching_combat_hero_after_initial_resources_have_changed()
    {
        $original = CombatHeroFactory::new()->create();
        $original->receiveDamage(10);
        $original->setCurrentStamina($original->getCurrentStamina() - 5);
        $original->setCurrentMana($original->getCurrentMana() - 5);

        /** @var CombatHeroDataMapper $mapper */
        $mapper = app(CombatHeroDataMapper::class);
        $combatHero = $mapper->getCombatHero($original->toArray());
        $this->assertTrue($original == $combatHero);
    }

    /**
     * @test
     */
    public function it_will_map_to_matching_combat_hero_if_inherited_combat_positions_changed()
    {
        $original = CombatHeroFactory::new()->create();
        $initialCombatPosition = $original->getInitialCombatPosition();
        $inheritedPositions = CombatPosition::query()
            ->where('id', '!=', $initialCombatPosition->id)
            ->take(2)->get();
        $original->setInheritedCombatPositions($inheritedPositions->values());


        /** @var CombatHeroDataMapper $mapper */
        $mapper = app(CombatHeroDataMapper::class);
        $combatHero = $mapper->getCombatHero($original->toArray());
        $this->assertTrue($original == $combatHero);
    }
}