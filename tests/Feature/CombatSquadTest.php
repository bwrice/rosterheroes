<?php

namespace Tests\Feature;

use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombatSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_front_line_hero_will_return_ready_attacks_with_all_attacker_positions()
    {
        // set speed to 100 so it's always ready
        $heroCombatAttackFactory = HeroCombatAttackFactory::new()->withCombatSpeed(100);
        $frontLineAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE);
        $backLineAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE);
        $highGroundAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND);

        $frontLineHeroFactory = CombatHeroFactory::new()->withHeroCombatAttacks(collect([
            $frontLineAttackFactory,
            $backLineAttackFactory,
            $highGroundAttackFactory
        ]))->withCombatPosition(CombatPosition::FRONT_LINE);

        $backLineHeroFactory = CombatHeroFactory::new()->withCombatPosition(CombatPosition::BACK_LINE);

        $combatSquad = CombatSquadFactory::new()->withCombatHeroFactories(collect([
            $frontLineHeroFactory,
            $backLineHeroFactory
        ]))->create();

        $combatSquad->updateCombatPositions(CombatPosition::all());

        $readyAttacks = $combatSquad->getReadyAttacks(1); //moment shouldn't matter
        $this->assertEquals(3, $readyAttacks->count());
    }

    /**
     * @test
     */
    public function a_back_line_hero_wont_have_front_line_attacks_available_if_a_front_line_hero_exists()
    {
        // set speed to 100 so it's always ready
        $heroCombatAttackFactory = HeroCombatAttackFactory::new()->withCombatSpeed(100);
        $frontLineAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE);
        $backLineAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE);
        $highGroundAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND);

        $frontLineHeroFactory = CombatHeroFactory::new()->withHeroCombatAttacks(collect([
            $frontLineAttackFactory,
            $backLineAttackFactory,
            $highGroundAttackFactory
        ]))->withCombatPosition(CombatPosition::BACK_LINE);

        $backLineHeroFactory = CombatHeroFactory::new()->withCombatPosition(CombatPosition::FRONT_LINE);

        $combatSquad = CombatSquadFactory::new()->withCombatHeroFactories(collect([
            $frontLineHeroFactory,
            $backLineHeroFactory
        ]))->create();

        $combatSquad->updateCombatPositions(CombatPosition::all());

        $readyAttacks = $combatSquad->getReadyAttacks(1); //moment shouldn't matter
        $this->assertEquals(2, $readyAttacks->count());
    }

    /**
     * @test
     */
    public function a_high_ground_hero_will_have_combat_attacks_for_all_attacker_positions_if_no_back_or_front_line_heroes()
    {
        // set speed to 100 so it's always ready
        $heroCombatAttackFactory = HeroCombatAttackFactory::new()->withCombatSpeed(100);
        $frontLineAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE);
        $backLineAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE);
        $highGroundAttackFactory = $heroCombatAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND);

        $frontLineHeroFactory = CombatHeroFactory::new()->withHeroCombatAttacks(collect([
            $frontLineAttackFactory,
            $backLineAttackFactory,
            $highGroundAttackFactory
        ]))->withCombatPosition(CombatPosition::HIGH_GROUND);

        // Another high-ground hero
        $backLineHeroFactory = CombatHeroFactory::new()->withCombatPosition(CombatPosition::HIGH_GROUND);

        $combatSquad = CombatSquadFactory::new()->withCombatHeroFactories(collect([
            $frontLineHeroFactory,
            $backLineHeroFactory
        ]))->create();

        $combatSquad->updateCombatPositions(CombatPosition::all());

        $readyAttacks = $combatSquad->getReadyAttacks(1); //moment shouldn't matter
        $this->assertEquals(3, $readyAttacks->count());
    }

    /**
     * @test
     */
    public function it_will_not_return_an_attack_if_the_hero_has_zero_of_the_resource_cost()
    {
        $resourceCost = new FixedResourceCost(MeasurableType::STAMINA, 5);

        // set speed to 100 so it's always ready
        $staminaCostingAttackFactory = HeroCombatAttackFactory::new()
            ->withResourceCosts(new ResourceCostsCollection([
                $resourceCost
            ]))
            ->withCombatSpeed(100)
            ->withAttackerPosition(CombatPosition::FRONT_LINE);

        $anotherAttackFactory = HeroCombatAttackFactory::new()
            ->withCombatSpeed(100)
            ->withAttackerPosition(CombatPosition::FRONT_LINE);

        $combatHeroFactory = CombatHeroFactory::new()
            ->withHeroCombatAttacks(collect([
                $staminaCostingAttackFactory,
                $anotherAttackFactory
            ]))
            ->withCombatPosition(CombatPosition::FRONT_LINE);

        $combatSquad = CombatSquadFactory::new()->withCombatHeroFactories(collect([
            $combatHeroFactory
        ]))->create();

        $this->assertEquals(2, $combatSquad->getReadyAttacks(1)->count());

        /** @var CombatHero $combatHero */
        $combatHero = $combatSquad->getCombatHeroes()->first();
        $combatHero->setCurrentStamina(0);

        $this->assertEquals(1, $combatSquad->getReadyAttacks(1)->count());
    }

    /**
     * @test
     */
    public function it_wont_filter_attacks_with_resource_costs_if_the_hero_has_even_just_one_point_left()
    {
        $resourceCost = new FixedResourceCost(MeasurableType::MANA, 99);

        // set speed to 100 so it's always ready
        $staminaCostingAttackFactory = HeroCombatAttackFactory::new()
            ->withResourceCosts(new ResourceCostsCollection([
                $resourceCost
            ]))
            ->withCombatSpeed(100)
            ->withAttackerPosition(CombatPosition::FRONT_LINE);

        $anotherAttackFactory = HeroCombatAttackFactory::new()
            ->withCombatSpeed(100)
            ->withAttackerPosition(CombatPosition::FRONT_LINE);

        $combatHeroFactory = CombatHeroFactory::new()
            ->withHeroCombatAttacks(collect([
                $staminaCostingAttackFactory,
                $anotherAttackFactory
            ]))
            ->withCombatPosition(CombatPosition::FRONT_LINE);

        $combatSquad = CombatSquadFactory::new()->withCombatHeroFactories(collect([
            $combatHeroFactory
        ]))->create();

        $this->assertEquals(2, $combatSquad->getReadyAttacks(1)->count());

        /** @var CombatHero $combatHero */
        $combatHero = $combatSquad->getCombatHeroes()->first();
        $combatHero->setCurrentMana(1);

        $this->assertEquals(2, $combatSquad->getReadyAttacks(1)->count());
    }

    /**
     * @test
     */
    public function it_will_filter_an_attack_with_multiple_resource_costs_if_either_resource_is_missing()
    {
        $staminaCost = new FixedResourceCost(MeasurableType::STAMINA, 10);
        $manaCost = new FixedResourceCost(MeasurableType::MANA, 10);

        // set speed to 100 so it's always ready
        $staminaCostingAttackFactory = HeroCombatAttackFactory::new()
            ->withResourceCosts(new ResourceCostsCollection([
                $staminaCost,
                $manaCost
            ]))
            ->withCombatSpeed(100)
            ->withAttackerPosition(CombatPosition::FRONT_LINE);

        $anotherAttackFactory = HeroCombatAttackFactory::new()
            ->withCombatSpeed(100)
            ->withAttackerPosition(CombatPosition::FRONT_LINE);

        $combatHeroFactory = CombatHeroFactory::new()
            ->withHeroCombatAttacks(collect([
                $staminaCostingAttackFactory,
                $anotherAttackFactory
            ]))
            ->withCombatPosition(CombatPosition::FRONT_LINE);

        $combatSquad = CombatSquadFactory::new()->withCombatHeroFactories(collect([
            $combatHeroFactory
        ]))->create();

        $this->assertEquals(2, $combatSquad->getReadyAttacks(1)->count());

        /** @var CombatHero $combatHero */
        $combatHero = $combatSquad->getCombatHeroes()->first();
        $combatHero->setCurrentMana(0);

        $this->assertEquals(1, $combatSquad->getReadyAttacks(1)->count());
    }
}
