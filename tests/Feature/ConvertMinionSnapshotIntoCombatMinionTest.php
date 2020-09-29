<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Actions\ConvertMinionSnapshotIntoCombatMinion;
use App\Domain\Models\AttackSnapshot;
use App\Factories\Models\AttackSnapshotFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemSnapshotFactory;
use App\Factories\Models\MinionSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertMinionSnapshotIntoCombatMinionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertMinionSnapshotIntoCombatMinion
     */
    protected function getDomainAction()
    {
        return app(ConvertMinionSnapshotIntoCombatMinion::class);
    }

    /**
     * @test
     */
    public function it_will_convert_a_minion_snapshot_into_a_combat_minion_with_matching_properties()
    {
        $minionSnapshot = MinionSnapshotFactory::new()->create();

        $combatMinion = $this->getDomainAction()->execute($minionSnapshot);

        $this->assertEquals($minionSnapshot->uuid, $combatMinion->getSourceUuid());
        $this->assertEquals($minionSnapshot->protection, $combatMinion->getProtection());
        $this->assertEquals($minionSnapshot->starting_health, $combatMinion->getCurrentHealth());
        $this->assertEquals($minionSnapshot->combat_position_id, $combatMinion->getInitialCombatPositionID());
        $this->assertTrue(abs($minionSnapshot->block_chance - $combatMinion->getBlockChancePercent()) < 0.01);
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

        $combatHero = $this->getDomainAction()->execute($minionSnapshot);
        $this->assertEquals($attackSnapshots->count(), $combatHero->getCombatAttacks()->count());
    }
}
