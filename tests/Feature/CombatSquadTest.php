<?php

namespace Tests\Feature;

use App\Domain\Models\CombatPosition;
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

        $combatSquad = CombatSquadFactory::new()->withCombatHeroes(collect([
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

        $combatSquad = CombatSquadFactory::new()->withCombatHeroes(collect([
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

        $combatSquad = CombatSquadFactory::new()->withCombatHeroes(collect([
            $frontLineHeroFactory,
            $backLineHeroFactory
        ]))->create();

        $combatSquad->updateCombatPositions(CombatPosition::all());

        $readyAttacks = $combatSquad->getReadyAttacks(1); //moment shouldn't matter
        $this->assertEquals(3, $readyAttacks->count());
    }
}
