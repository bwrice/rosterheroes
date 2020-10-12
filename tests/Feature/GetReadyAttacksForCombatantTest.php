<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\GetReadyAttacksForCombatant;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\CombatPosition;
use App\Facades\CombatPositionFacade;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetReadyAttacksForCombatantTest extends TestCase
{
    /**
     * @return GetReadyAttacksForCombatant
     */
    protected function getDomainAction()
    {
        return app(GetReadyAttacksForCombatant::class);
    }

    /**
     * @test
     */
    public function it_will_return_attacks_from_all_combat_positions_for_front_line_proximity()
    {
        $alwaysReadyAttackFactory = CombatAttackFactory::new()->withCombatSpeed(100);
        $combatAttacks = collect();
        $combatAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE)->create());
        $combatAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE)->create());
        $combatAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND)->create());

        $combatant = CombatantFactory::new()->withCombatAttacks($combatAttacks)->create();
        $frontLineProximityID = CombatPositionFacade::id(CombatPosition::FRONT_LINE);

        $readyAttacks = $this->getDomainAction()->execute($combatant, $frontLineProximityID);

        $this->assertEquals($combatAttacks->count(), $readyAttacks->count());
        $combatAttacks->each(function (CombatAttack $combatAttack) use ($readyAttacks) {

            $match = $readyAttacks->first(function (CombatAttack $readyAttack) use ($combatAttack) {
                return $readyAttack->getSourceUuid() === $combatAttack->getSourceUuid();
            });
            $this->assertNotNull($match);
        });
    }

    /**
     * @test
     */
    public function it_will_return_only_back_line_and_high_ground_attacks_from_high_ground_proximity()
    {
        $alwaysReadyAttackFactory = CombatAttackFactory::new()->withCombatSpeed(100);
        $expectedReadyAttacks = collect();
        $expectedReadyAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE)->create());
        $expectedReadyAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND)->create());

        $totalCombatAttacks = clone $expectedReadyAttacks;
        $totalCombatAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE)->create());

        $combatant = CombatantFactory::new()->withCombatAttacks($totalCombatAttacks)->create();
        $backLineProximity = CombatPositionFacade::id(CombatPosition::BACK_LINE);

        $readyAttacks = $this->getDomainAction()->execute($combatant, $backLineProximity);

        $this->assertEquals($expectedReadyAttacks->count(), $readyAttacks->count());
        $expectedReadyAttacks->each(function (CombatAttack $expectedAttack) use ($readyAttacks) {
            $match = $readyAttacks->first(function (CombatAttack $readyAttack) use ($expectedAttack) {
                return $readyAttack->getSourceUuid() === $expectedAttack->getSourceUuid();
            });
            $this->assertNotNull($match);
        });
    }

    /**
     * @test
     */
    public function it_will_return_only_high_ground_attacks_from_high_ground_proximity()
    {
        $alwaysReadyAttackFactory = CombatAttackFactory::new()->withCombatSpeed(100);
        $expectedReadyAttacks = collect();
        $expectedReadyAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND)->create());

        $totalCombatAttacks = clone $expectedReadyAttacks;
        $totalCombatAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE)->create());
        $totalCombatAttacks->push($alwaysReadyAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE)->create());

        $combatant = CombatantFactory::new()->withCombatAttacks($totalCombatAttacks)->create();
        $highGroundProximity = CombatPositionFacade::id(CombatPosition::HIGH_GROUND);

        $readyAttacks = $this->getDomainAction()->execute($combatant, $highGroundProximity);

        $this->assertEquals($expectedReadyAttacks->count(), $readyAttacks->count());
        $expectedReadyAttacks->each(function (CombatAttack $expectedAttack) use ($readyAttacks) {
            $match = $readyAttacks->first(function (CombatAttack $readyAttack) use ($expectedAttack) {
                return $readyAttack->getSourceUuid() === $expectedAttack->getSourceUuid();
            });
            $this->assertNotNull($match);
        });
    }

    /**
     * @test
     */
    public function it_will_not_return_attacks_that_are_not_ready()
    {
        $neverReadyAttackFactory = CombatAttackFactory::new()->withCombatSpeed(0);
        $combatAttacks = collect();
        $combatAttacks->push($neverReadyAttackFactory->withAttackerPosition(CombatPosition::FRONT_LINE)->create());
        $combatAttacks->push($neverReadyAttackFactory->withAttackerPosition(CombatPosition::BACK_LINE)->create());
        $combatAttacks->push($neverReadyAttackFactory->withAttackerPosition(CombatPosition::HIGH_GROUND)->create());

        $combatant = CombatantFactory::new()->withCombatAttacks($combatAttacks)->create();
        $frontLineProximityID = CombatPositionFacade::id(CombatPosition::FRONT_LINE);

        $readyAttacks = $this->getDomainAction()->execute($combatant, $frontLineProximityID);

        $this->assertEquals(0, $readyAttacks->count());
    }
}
