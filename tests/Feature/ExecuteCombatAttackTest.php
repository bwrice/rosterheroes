<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ExecuteCombatAttack;
use App\Domain\Actions\Combat\ExecuteCombatAttackOnCombatant;
use App\Domain\Actions\Combat\FindTargetsForAttack;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ExecuteCombatAttackTest extends TestCase
{
    /**
     * @return ExecuteCombatAttack
     */
    protected function getDomainAction()
    {
        return app(ExecuteCombatAttack::class);
    }

    /**
     * @test
     */
    public function it_will_execute_combat_attack_on_each_combatant_returned_by_find_targets_action()
    {
        $combatants = collect();
        $combatantFactory = CombatantFactory::new();
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $combatants->push($combatantFactory->create());
        }
        $findTargetsMock = $this->getMockBuilder(FindTargetsForAttack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $findTargetsMock->expects($this->once())->method('execute')
            ->with($this->callback(function ($combatAttackArg) {
                return $combatAttackArg instanceof CombatAttackInterface;
            }), $this->callback(function (Collection $combatantArgs) use ($combatants) {
                return $combatantArgs->count() === $combatants->count();
            }))
            ->willReturn($combatants);

        $this->instance(FindTargetsForAttack::class, $findTargetsMock);

        $combatantUuids = $combatants->map(function (Combatant $combatant) {
            return $combatant->getSourceUuid();
        });

        $moment = rand(1, 100);

        $executeOnCombatantMock = $this->getMockBuilder(ExecuteCombatAttackOnCombatant::class)
            ->disableOriginalConstructor()
            ->getMock();

        $executeOnCombatantMock
            ->expects($this->exactly($combatants->count()))
            ->method('execute')
            ->with($this->callback(function ($combatAttack) {
                return $combatAttack instanceof CombatAttackInterface;
            }), $this->callback(function (Combatant $combatant) use ($combatantUuids) {
                $matchingKey = $combatantUuids->search($combatant->getSourceUuid());
                if ($matchingKey === false) {
                    return false;
                }
                $combatantUuids->forget($matchingKey);
                return true;
            }), $moment);

        $this->instance(ExecuteCombatAttackOnCombatant::class, $executeOnCombatantMock);

        $combatAttack = CombatAttackFactory::new()->create();

        $this->getDomainAction()->execute($combatAttack, $combatants, $moment);
    }
}
