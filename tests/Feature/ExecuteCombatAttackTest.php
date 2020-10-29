<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ExecuteCombatAttack;
use App\Domain\Actions\Combat\ExecuteCombatAttackOnCombatant;
use App\Domain\Actions\Combat\FindTargetsForAttack;
use App\Domain\Actions\Combat\SpendResourceCosts;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
        $attacker = $combatantFactory->create();
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
                // combat-attack
                return $combatAttack instanceof CombatAttackInterface;
            }) , $this->callback(function (Combatant $combatant) use ($attacker) {
                // attacker
                return $combatant->getSourceUuid() === $attacker->getSourceUuid();
            }), $this->callback(function (Combatant $combatant) use ($combatantUuids) {
                // target
                $matchingKey = $combatantUuids->search($combatant->getSourceUuid());
                if ($matchingKey === false) {
                    return false;
                }
                $combatantUuids->forget($matchingKey);
                return true;
            }), $moment);

        $this->instance(ExecuteCombatAttackOnCombatant::class, $executeOnCombatantMock);

        $combatAttack = CombatAttackFactory::new()->create();

        $this->getDomainAction()->execute($combatAttack, $attacker, $combatants, $moment);
    }

    /**
     * @test
     */
    public function it_will_return_a_collection_of_return_values_from_execute_on_combatant_action()
    {
        $attacker = CombatantFactory::new()->create();
        $dummyCombatant = CombatantFactory::new()->create();
        $findTargetsMock = \Mockery::mock(FindTargetsForAttack::class)
            ->shouldReceive('execute')
            ->andReturn(collect([
                $dummyCombatant,
                $dummyCombatant,
                $dummyCombatant
            ]))->getMock();

        $this->instance(FindTargetsForAttack::class, $findTargetsMock);

        // create a fake uuid return value that would normally be a combat event
        $uuid = (string) Str::uuid();
        $executeOnCombatantMock = \Mockery::mock(ExecuteCombatAttackOnCombatant::class)
            ->shouldReceive('execute')
            ->times(3)
            ->andReturn($uuid)
            ->getMock();

        $this->instance(ExecuteCombatAttackOnCombatant::class, $executeOnCombatantMock);

        $combatAttack = CombatAttackFactory::new()->create();

        $returnedCollection = $this->getDomainAction()->execute($combatAttack, $attacker, collect(), 1);
        $returnedCollection->shift(); // remove attack event added directly by action
        $this->assertEquals(3, $returnedCollection->count());
        $returnedCollection->each(function ($value) use ($uuid) {
            $this->assertEquals($uuid, $value);
        });
    }

    /**
     * @test
     */
    public function it_will_execute_spend_resources_for_each_resource_cost()
    {

        $attacker = CombatantFactory::new()->create();
        $dummyCombatant = CombatantFactory::new()->create();
        $findTargetsMock = \Mockery::mock(FindTargetsForAttack::class)
            ->shouldReceive('execute')
            ->andReturn(collect([
                $dummyCombatant
            ]))->getMock();

        $this->instance(FindTargetsForAttack::class, $findTargetsMock);

        $executeOnCombatantMock = \Mockery::mock(ExecuteCombatAttackOnCombatant::class)
            ->shouldReceive('execute')
            ->andReturn('anything')
            ->getMock();

        $this->instance(ExecuteCombatAttackOnCombatant::class, $executeOnCombatantMock);

        $combatAttack = CombatAttackFactory::new()->withResourceCosts(collect([
            new FixedResourceCost(MeasurableType::STAMINA, rand(1, 100)),
            new FixedResourceCost(MeasurableType::MANA, rand(1, 100))
        ]))->create();

        // What we're testing
        $spendResourcesMock = \Mockery::mock(SpendResourceCosts::class)
            ->shouldReceive('execute')
            ->times(2)
            ->getMock();
        $this->app->instance(SpendResourceCosts::class, $spendResourcesMock);

        $this->getDomainAction()->execute($combatAttack, $attacker, collect(), 1);
    }
}
