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
}
