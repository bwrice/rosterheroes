<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Actions\ConvertHeroSnapshotToCombatHero;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\AttackSnapshot;
use App\Domain\Models\MeasurableType;
use App\Factories\Models\AttackSnapshotFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertHeroSnapshotToCombatHeroTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertHeroSnapshotToCombatHero
     */
    protected function getDomainAction()
    {
        return app(ConvertHeroSnapshotToCombatHero::class);
    }

    /**
     * @test
     */
    public function it_will_convert_a_hero_snapshot_into_a_combat_hero_with_matching_properties()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withMeasurableSnapshots()->create();
        $combatHero = $this->getDomainAction()->execute($heroSnapshot);

        $this->assertEquals($combatHero->getSourceUuid(), $heroSnapshot->uuid);
        $this->assertEquals($combatHero->getProtection(), $heroSnapshot->protection);
        $this->assertEquals($combatHero->getInitialCombatPositionID(), $heroSnapshot->combat_position_id);
        $this->assertTrue(abs($combatHero->getBlockChancePercent() - $heroSnapshot->block_chance) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_create_a_combat_hero_with_matching_resource_amounts_for_the_hero_snapshot()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withMeasurableSnapshots()->create();
        $combatHero = $this->getDomainAction()->execute($heroSnapshot);

        $this->assertEquals($combatHero->getCurrentHealth(), $heroSnapshot->getMeasurableSnapshot(MeasurableType::HEALTH)->final_amount);
        $this->assertEquals($combatHero->getCurrentStamina(), $heroSnapshot->getMeasurableSnapshot(MeasurableType::STAMINA)->final_amount);
        $this->assertEquals($combatHero->getCurrentMana(), $heroSnapshot->getMeasurableSnapshot(MeasurableType::MANA)->final_amount);
    }

    /**
     * @test
     */
    public function it_will_execute_convert_to_combat_attack_for_attack_snapshots_belonging_to_a_hero_snapshots_item_snapshot()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withMeasurableSnapshots()->create();

        $attackSnapshots = collect();
        $itemSnapshotUuids = [];
        for ($i = 1; $i <= 2; $i++) {
            $itemSnapshot = ItemSnapshotFactory::new()->withHeroSnapshotID($heroSnapshot->id)->create();
            $itemSnapshotUuids[] = $itemSnapshot->uuid;
            $attackSnapshotFactory = AttackSnapshotFactory::new()->withAttacker($itemSnapshot);
            for ($j = 1; $j <= rand(2, 4); $j++) {
                $attackSnapshots->push($attackSnapshotFactory->create());
            }
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

        $combatHero = $this->getDomainAction()->execute($heroSnapshot);
        $this->assertEquals($attackSnapshots->count(), $combatHero->getCombatAttacks()->count());
    }
}
