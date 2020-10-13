<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ExecuteCombatAttack;
use App\Domain\Actions\Combat\GetReadyAttacksForCombatant;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\CombatGroups\CombatGroup;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RunCombatTurnTest extends TestCase
{
    /**
     * @return RunCombatTurn
     */
    protected function getDomainAction()
    {
        return app(RunCombatTurn::class);
    }

    /**
     * @test
     */
    public function it_will_execute_get_ready_attacks_for_each_attacker()
    {
        $combatantFactory = CombatantFactory::new();
        $combatants = collect();
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $combatants->push($combatantFactory->create());
        }

        $combatantUuids = $combatants->map(function (Combatant $combatant) {
            return $combatant->getSourceUuid();
        });

        $mock = $this->getMockBuilder(GetReadyAttacksForCombatant::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->exactly($combatants->count()))
            ->method('execute')
            ->with($this->callback(function (Combatant $attacker) use ($combatantUuids) {
                $matchingKey = $combatantUuids->search($attacker->getSourceUuid());
                if ($matchingKey === false) {
                    return false;
                }
                $combatantUuids->forget($matchingKey);
                return true;
        }))->willReturn(collect());

        $this->instance(GetReadyAttacksForCombatant::class, $mock);

        $attackingGroupMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('getPossibleAttackers')
            ->andReturn($combatants)
            ->getMock();
        $targetedGroupMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('getPossibleTargets')
            ->andReturn(collect())
            ->getMock();

        $this->getDomainAction()->execute($attackingGroupMock, $targetedGroupMock, 1);
    }

    /**
     * @test
     */
    public function it_will_execute_the_execute_attack_action_for_each_ready_attack_for_each_attacker()
    {
        $combatAttackFactory = CombatAttackFactory::new();
        $attackerOneAttacks = collect();
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $attackerOneAttacks->push($combatAttackFactory->create());
        }

        $attackerTwoAttacks = collect();
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $attackerTwoAttacks->push($combatAttackFactory->create());
        }

        $attackerOne = CombatantFactory::new()->create();
        $attackerTwo = CombatantFactory::new()->create();

        $getReadyAttacksMock = $this->getMockBuilder(GetReadyAttacksForCombatant::class)
            ->disableOriginalConstructor()
            ->getMock();

        $getReadyAttacksMock->method('execute')->willReturn($this->returnCallback(function (Combatant $attacker) use ($attackerOne, $attackerOneAttacks, $attackerTwoAttacks) {
            if ($attacker->getSourceUuid() === $attackerOne->getSourceUuid()) {
                return $attackerOneAttacks;
            }
            return $attackerTwoAttacks;
        }));

        $this->instance(GetReadyAttacksForCombatant::class, $getReadyAttacksMock);

        $executeCombatAttackMock = $this->getMockBuilder(ExecuteCombatAttack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $executeCombatAttackMock->expects($this->exactly($attackerOneAttacks->count() + $attackerTwoAttacks->count()))->method('execute');

        $this->instance(ExecuteCombatAttack::class, $executeCombatAttackMock);

        $attackingGroupMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('getPossibleAttackers')
            ->andReturn(collect([$attackerOne, $attackerTwo]))
            ->getMock();
        $targetedGroupMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('getPossibleTargets')
            ->andReturn(collect())
            ->getMock();

        $this->getDomainAction()->execute($attackingGroupMock, $targetedGroupMock, 1);
    }

    /**
     * @test
     */
    public function it_will_return_a_flattened_collection_of_all_combat_events_returned_from_execute_combat_attack()
    {

        $attackerOne = CombatantFactory::new()->create();
        $attackerTwo = CombatantFactory::new()->create();

        $combatAttackFactory = CombatAttackFactory::new();
        $attackOne = $combatAttackFactory->create();
        $attackTwo = $combatAttackFactory->create();
        $attackerOneAttacks = collect([
            $attackOne,
            $attackTwo,
        ]);
        $attackThree = $combatAttackFactory->create();
        $attackerTwoAttacks = collect([
            $attackThree
        ]);

        $getReadyAttacksMock = $this->getMockBuilder(GetReadyAttacksForCombatant::class)
            ->disableOriginalConstructor()
            ->getMock();

        $getReadyAttacksMock->method('execute')->willReturn($this->returnCallback(function (Combatant $attacker) use ($attackerOne, $attackerOneAttacks, $attackerTwoAttacks) {
            if ($attacker->getSourceUuid() === $attackerOne->getSourceUuid()) {
                return $attackerOneAttacks;
            }
            return $attackerTwoAttacks;
        }));

        $this->instance(GetReadyAttacksForCombatant::class, $getReadyAttacksMock);

        $executeCombatAttackMock = $this->getMockBuilder(ExecuteCombatAttack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $attackOneEvents = collect([
            'dummy-value-1',
            'dummy-value-2',
        ]);

        $attackTwoEvents = collect([
            'dummy-value-3',
            'dummy-value-4',
            'dummy-value-5'
        ]);

        $attackThreeEvents = collect([
            'dummy-value-6',
            'dummy-value-7',
            'dummy-value-9',
            'dummy-value-10',
            'dummy-value-11'
        ]);

        $executeCombatAttackMock->method('execute')->willReturn($this->returnCallback(function (CombatAttackInterface $combatAttack) use ($attackOne, $attackTwo, $attackOneEvents, $attackTwoEvents, $attackThreeEvents) {
            if ($combatAttack->getUuid() === $attackOne->getUuid()) {
                return $attackOneEvents;
            }
            if ($combatAttack->getUuid() === $attackTwo->getUuid()) {
                return $attackTwoEvents;
            }
            return $attackThreeEvents;
        }));

        $expectedReturnCollection = $attackOneEvents->merge($attackTwoEvents->merge($attackThreeEvents));

        $this->instance(ExecuteCombatAttack::class, $executeCombatAttackMock);

        $attackingGroupMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('getPossibleAttackers')
            ->andReturn(collect([$attackerOne, $attackerTwo]))
            ->getMock();
        $targetedGroupMock = \Mockery::mock(CombatGroup::class)
            ->shouldReceive('getPossibleTargets')
            ->andReturn(collect())
            ->getMock();

        $returnCollection = $this->getDomainAction()->execute($attackingGroupMock, $targetedGroupMock, 1);
        $this->assertEquals($expectedReturnCollection->count(), $returnCollection->count());
        $returnCollection->each(function ($returnValue) use ($expectedReturnCollection) {
            $match = $expectedReturnCollection->first(function ($expectedValue) use ($returnValue) {
                return $expectedValue === $returnValue;
            });
            $this->assertNotNull($match);
        });
    }
}
