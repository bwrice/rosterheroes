<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\GetClosestInheritedCombatPosition;
use App\Domain\Models\CombatPosition;
use App\Factories\Combat\CombatantFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetClosestInheritedCombatPositionTest extends TestCase
{
    /**
     * @return GetClosestInheritedCombatPosition
     */
    protected function getDomainAction()
    {
        return app(GetClosestInheritedCombatPosition::class);
    }

    /**
     * @test
     * @param $combatPositionName
     * @dataProvider provides_it_will_return_front_line_combat_position_for_any_lone_combatant
     */
    public function it_will_return_front_line_combat_position_for_any_lone_combatant($combatPositionName)
    {
        $combatant = CombatantFactory::new()->withCombatPosition($combatPositionName)->create();
        $combatPosition = $this->getDomainAction()->execute($combatant, collect([$combatant]));
        $this->assertEquals(CombatPosition::FRONT_LINE, $combatPosition->name);
    }

    public function provides_it_will_return_front_line_combat_position_for_any_lone_combatant()
    {
        return [
            [
                'combatPositionName' => CombatPosition::FRONT_LINE
            ],
            [
                'combatPositionName' => CombatPosition::BACK_LINE
            ],
            [
                'combatPositionName' => CombatPosition::HIGH_GROUND
            ]
        ];
    }

    /**
     * @test
     */
    public function it_will_return_front_line_for_a_back_line_combatant_with_a_high_ground_combatant()
    {
        $backLineCombatant = CombatantFactory::new()->withCombatPosition(CombatPosition::BACK_LINE)->create();
        $highGroundCombatant = CombatantFactory::new()->withCombatPosition(CombatPosition::HIGH_GROUND)->create();
        $combatants = collect([
            $backLineCombatant,
            $highGroundCombatant,
        ]);
        $combatPosition = $this->getDomainAction()->execute($backLineCombatant, $combatants);
        $this->assertEquals(CombatPosition::FRONT_LINE, $combatPosition->name);
    }

    /**
     * @test
     */
    public function it_will_return_high_ground_for_a_high_ground_combatant_with_a_back_line_combatant()
    {
        $backLineCombatant = CombatantFactory::new()->withCombatPosition(CombatPosition::BACK_LINE)->create();
        $highGroundCombatant = CombatantFactory::new()->withCombatPosition(CombatPosition::HIGH_GROUND)->create();
        $combatants = collect([
            $backLineCombatant,
            $highGroundCombatant,
        ]);
        $combatPosition = $this->getDomainAction()->execute($highGroundCombatant, $combatants);
        $this->assertEquals(CombatPosition::HIGH_GROUND, $combatPosition->name);
    }

    /**
     * @test
     * @param $combatPositionName
     * @dataProvider provides_if_there_is_a_front_line_combatant_it_will_return_back_line_and_high_ground_for_those_combatants
     */
    public function if_there_is_another_front_line_combatant_it_will_return_the_combatants_actual_combat_position($combatPositionName)
    {
        $combatant = CombatantFactory::new()->withCombatPosition($combatPositionName)->create();
        $frontLineCombatant = CombatantFactory::new()->withCombatPosition(CombatPosition::FRONT_LINE)->create();
        $combatants = collect([
            $combatant,
            $frontLineCombatant,
        ]);
        $combatPosition = $this->getDomainAction()->execute($combatant, $combatants);
        $this->assertEquals($combatPositionName, $combatPosition->name);
    }

    public function provides_if_there_is_a_front_line_combatant_it_will_return_back_line_and_high_ground_for_those_combatants()
    {
        return [
            [
                'combatPositionName' => CombatPosition::FRONT_LINE
            ],
            [
                'combatPositionName' => CombatPosition::BACK_LINE
            ],
            [
                'combatPositionName' => CombatPosition::HIGH_GROUND
            ]
        ];
    }
}
