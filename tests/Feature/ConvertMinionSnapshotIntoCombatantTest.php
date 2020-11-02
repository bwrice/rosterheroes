<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Actions\ConvertMinionSnapshotIntoCombatant;
use App\Domain\Models\AttackSnapshot;
use App\Factories\Models\AttackSnapshotFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemSnapshotFactory;
use App\Factories\Models\MinionSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertMinionSnapshotIntoCombatantTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertMinionSnapshotIntoCombatant
     */
    protected function getDomainAction()
    {
        return app(ConvertMinionSnapshotIntoCombatant::class);
    }

    /**
     * @test
     */
    public function it_will_convert_a_minion_snapshot_into_a_combat_minion_with_matching_properties()
    {
        $minionSnapshot = MinionSnapshotFactory::new()->create();

        $combatant = $this->getDomainAction()->execute($minionSnapshot);

        $this->assertEquals($minionSnapshot->uuid, $combatant->getSourceUuid());
        $this->assertEquals($minionSnapshot->protection, $combatant->getProtection());
        $this->assertEquals($minionSnapshot->starting_health, $combatant->getInitialHealth());
        $this->assertEquals($minionSnapshot->starting_health, $combatant->getCurrentHealth());
        $this->assertEquals($minionSnapshot->starting_stamina, $combatant->getInitialStamina());
        $this->assertEquals($minionSnapshot->starting_stamina, $combatant->getCurrentStamina());
        $this->assertEquals($minionSnapshot->starting_mana, $combatant->getInitialMana());
        $this->assertEquals($minionSnapshot->starting_mana, $combatant->getCurrentMana());
        $this->assertEquals($minionSnapshot->combat_position_id, $combatant->getCombatPositionID());
        $this->assertTrue(abs($minionSnapshot->block_chance - $combatant->getBlockChancePercent()) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_execute_convert_combat_attack_for_each_attack_snapshot_of_the_minion_snapshot()
    {
        $minionSnapshot = MinionSnapshotFactory::new()->create();

        $attackSnapshots = collect();
        $attackSnapshotFactory = AttackSnapshotFactory::new()->withAttacker($minionSnapshot);
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $attackSnapshots->push($attackSnapshotFactory->create());
        }

        $this->assertGreaterThan(1, $attackSnapshots->count());

        $attackSnapshotUuids = $attackSnapshots->map(function (AttackSnapshot $attackSnapshot) {
            return $attackSnapshot->uuid;
        });

        $convertCombatAttackMock = $this->getMockBuilder(ConvertAttackSnapshotToCombatAttack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $convertCombatAttackMock->expects($this->exactly($attackSnapshots->count()))
            ->method('execute')
            ->with($this->callback(function (AttackSnapshot $attackSnapshot) use ($attackSnapshotUuids) {
                $matchingKey = $attackSnapshotUuids->search($attackSnapshot->uuid);
                if ($matchingKey === false) {
                    return false;
                }
                $attackSnapshotUuids->forget($matchingKey);
                return true;
            }))
            ->willReturn('anything');

        $this->instance(ConvertAttackSnapshotToCombatAttack::class, $convertCombatAttackMock);

        $combatant = $this->getDomainAction()->execute($minionSnapshot);
        $this->assertEquals($attackSnapshots->count(), $combatant->getCombatAttacks()->count());
    }
}
