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
     */
    public function it_will_return_front_line_heroes_for_a_front_line_attack()
    {
        $attack = CombatAttackFactory::new()
            ->withTargetPosition(CombatPosition::FRONT_LINE)
            ->withMaxTargetCount(1)
            ->create();
        $frontLineCombatant = CombatantFactory::new()
            ->withCombatPosition(CombatPosition::FRONT_LINE)
            ->create();
        $backLineCombatant = CombatantFactory::new()
            ->withCombatPosition(CombatPosition::BACK_LINE)
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
}
