<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\FindTargetsForAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Facades\DamageTypeFacade;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindTargetsForAttackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindTargetsForAttack
     */
    protected function getDomainAction()
    {
        return app(FindTargetsForAttack::class);
    }

    /**
     * @test
     * @param $targetPositionName
     * @param $extraCombatantPositionName
     * @dataProvider provides_it_will_return_front_line_heroes_for_a_front_line_attack
     */
    public function it_will_return_front_line_heroes_for_a_front_line_attack($targetPositionName, $extraCombatantPositionName)
    {
        $attack = CombatAttackFactory::new()
            ->withtargetPosition($targetPositionName)
            ->withTargetsCount(1)
            ->create();
        $expectedTargetCombatant = CombatantFactory::new()
            ->withCombatPosition($targetPositionName)
            ->create();
        $otherTargetCombatant = CombatantFactory::new()
            ->withCombatPosition($extraCombatantPositionName)
            ->create();

        $combatants = collect([
            $expectedTargetCombatant,
            $otherTargetCombatant
        ]);

        $targets = $this->getDomainAction()->execute($attack, $combatants);
        $this->assertEquals(1, $targets->count());
        /** @var CombatHero $combatant */
        $combatant = $targets->first();
        $this->assertEquals($expectedTargetCombatant->getSourceUuid(), $combatant->getSourceUuid());
    }

    public function provides_it_will_return_front_line_heroes_for_a_front_line_attack()
    {
        return [
            'front-line attack' => [
                'targetPositionName' => CombatPosition::FRONT_LINE,
                'extraCombatantPositionName' => CombatPosition::BACK_LINE,
            ],
            'back-line attack' => [
                'targetPositionName' => CombatPosition::BACK_LINE,
                'extraCombatantPositionName' => CombatPosition::FRONT_LINE,
            ],
            'high-ground attack' => [
                'targetPositionName' => CombatPosition::HIGH_GROUND,
                'extraCombatantPositionName' => CombatPosition::BACK_LINE,
            ],
        ];
    }

    /**
     * @test
     * @param $attackTargetPositionName
     * @param $expectedCombatantTargetPosition
     * @param $otherCombatantTargetPosition
     * @dataProvider provides_it_will_return_targets_from_the_closest_proximity_combat_position_if_non_match_the_target_position
     */
    public function it_will_return_targets_from_the_closest_proximity_combat_position_if_non_match_the_target_position(
        $attackTargetPositionName,
        $expectedCombatantTargetPosition,
        $otherCombatantTargetPosition
    )
    {
        $attack = CombatAttackFactory::new()
            ->withtargetPosition($attackTargetPositionName)
            ->withTargetsCount(1)
            ->create();

        $combatants = collect();

        $expectedTargetCombatant = CombatantFactory::new()
            ->withCombatPosition($expectedCombatantTargetPosition)
            ->create();
        $combatants->push($expectedTargetCombatant);

        if ($otherCombatantTargetPosition) {
            $otherTargetCombatant = CombatantFactory::new()
                ->withCombatPosition($otherCombatantTargetPosition)
                ->create();
            $combatants->push($otherTargetCombatant);
        }

        $targets = $this->getDomainAction()->execute($attack, $combatants);
        $this->assertEquals(1, $targets->count());
        /** @var CombatHero $combatant */
        $combatant = $targets->first();
        $this->assertEquals($expectedTargetCombatant->getSourceUuid(), $combatant->getSourceUuid());
    }

    public function provides_it_will_return_targets_from_the_closest_proximity_combat_position_if_non_match_the_target_position()
    {
        return [
            [
                'attackTargetPositionName' => CombatPosition::FRONT_LINE,
                'expectedCombatantTargetPosition' => CombatPosition::BACK_LINE,
                'otherCombatantTargetPosition' => CombatPosition::HIGH_GROUND,
            ],
            [
                'attackTargetPositionName' => CombatPosition::BACK_LINE,
                'expectedCombatantTargetPosition' => CombatPosition::FRONT_LINE,
                'otherCombatantTargetPosition' => CombatPosition::HIGH_GROUND,
            ],
            [
                'attackTargetPositionName' => CombatPosition::HIGH_GROUND,
                'expectedCombatantTargetPosition' => CombatPosition::BACK_LINE,
                'otherCombatantTargetPosition' => null,
            ]
        ];
    }

    /**
     * @test
     */
    public function if_enough_targets_are_available_it_will_return_the_max_target_count_of_targets()
    {
        $targetsCount = rand(2, 4);

        $attack = CombatAttackFactory::new()
            ->withtargetPosition(CombatPosition::FRONT_LINE)
            ->withTargetsCount($targetsCount)
            ->create();

        $maxTargetsCount = DamageTypeFacade::maxTargetsCount($attack->getDamageTypeID(), $attack->getTier(), $targetsCount);

        $combatants = collect();
        $combatantFactory = CombatantFactory::new()->withCombatPosition(CombatPosition::FRONT_LINE);
        for ($i = 1; $i <= $maxTargetsCount + 2; $i++) {
            $combatants->push($combatantFactory->create());
        }

        $targets = $this->getDomainAction()->execute($attack, $combatants);
        $this->assertEquals($maxTargetsCount, $targets->count());
    }
}
