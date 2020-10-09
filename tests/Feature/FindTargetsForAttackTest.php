<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\FindTargetsForAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
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
            ->withMaxTargetCount(1)
            ->create();
        $frontLineCombatant = CombatantFactory::new()
            ->withCombatPosition($targetPositionName)
            ->create();
        $backLineCombatant = CombatantFactory::new()
            ->withCombatPosition($extraCombatantPositionName)
            ->create();

        $combatants = collect([
            $frontLineCombatant,
            $backLineCombatant
        ]);

        $targets = $this->getDomainAction()->execute($attack, $combatants);
        $this->assertEquals(1, $targets->count());
        /** @var CombatHero $combatant */
        $combatant = $targets->first();
        $this->assertEquals($frontLineCombatant->getSourceUuid(), $combatant->getSourceUuid());
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
}
