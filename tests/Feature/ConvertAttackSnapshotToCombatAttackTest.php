<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ConvertAttackSnapshotToCombatAttack;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Factories\Models\AttackSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertAttackSnapshotToCombatAttackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertAttackSnapshotToCombatAttack
     */
    protected function getDomainAction()
    {
        return app(ConvertAttackSnapshotToCombatAttack::class);
    }

    /**
     * @test
     */
    public function it_will_convert_an_attack_snapshot_into_a_combat_attack()
    {
        $attackSnapshot = AttackSnapshotFactory::new()->create();

        $combatAttack = $this->getDomainAction()->execute($attackSnapshot);
        $this->assertEquals((string) $attackSnapshot->uuid, (string) $combatAttack->getSourceUuid());
        $this->assertEquals($attackSnapshot->name, $combatAttack->getName());
        $this->assertEquals($attackSnapshot->attacker->getUuid(), $combatAttack->getAttackerUuid());
        $this->assertEquals($attackSnapshot->damage, $combatAttack->getDamage());
        $this->assertTrue(abs($attackSnapshot->combat_speed - $combatAttack->getCombatSpeed()) < 0.01);
        $this->assertEquals($attackSnapshot->tier, $combatAttack->getTier());
        $this->assertEquals($attackSnapshot->targets_count, $combatAttack->getTargetsCount());
        $this->assertEquals($attackSnapshot->attacker_position_id, $combatAttack->getAttackerPositionID());
        $this->assertEquals($attackSnapshot->target_position_id, $combatAttack->getTargetPositionID());
        $this->assertEquals($attackSnapshot->target_priority_id, $combatAttack->getTargetPriorityID());
        $this->assertEquals($attackSnapshot->damage_type_id, $combatAttack->getDamageTypeID());
    }

    /**
     * @test
     */
    public function it_will_create_a_combat_attack_with_matching_resource_costs_of_the_attack_snapshot()
    {
        $attackSnapshot = AttackSnapshotFactory::new()->withResourceCosts()->create();

        $combatAttack = $this->getDomainAction()->execute($attackSnapshot);

        $combatAttackResourceCosts = $combatAttack->getResourceCosts();
        $this->assertTrue($combatAttackResourceCosts->isNotEmpty());

        $snapshotResourceCosts = $attackSnapshot->resource_costs;

        $combatAttackResourceCosts->each(function (ResourceCost $resourceCost) use ($snapshotResourceCosts) {
            $match = $snapshotResourceCosts->first(function (ResourceCost $snapshotResourceCost) use ($resourceCost) {
                if ($snapshotResourceCost instanceof FixedResourceCost) {
                    if (! $resourceCost instanceof FixedResourceCost) {
                        return false;
                    }
                    return $snapshotResourceCost->getAmount() === $resourceCost->getAmount()
                        && $snapshotResourceCost->getResourceName() === $resourceCost->getResourceName();
                } else {
                    /** @var PercentResourceCost $snapshotResourceCost */
                    if (! $resourceCost instanceof PercentResourceCost) {
                        return false;
                    }
                    return abs($snapshotResourceCost->getPercent() - $resourceCost->getPercent()) < PHP_FLOAT_EPSILON
                        && $snapshotResourceCost->getResourceName() === $resourceCost->getResourceName();
                }
            });
            $this->assertNotNull($match);
        });
    }
}
