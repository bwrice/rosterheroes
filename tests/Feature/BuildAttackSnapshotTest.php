<?php

namespace Tests\Feature;

use App\Domain\Actions\Snapshots\BuildAttackSnapshot;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Models\AttackSnapshot;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemSnapshot;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildAttackSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildAttackSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildAttackSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_build_an_attack_snapshot_for_an_attack_and_hero_snapshot()
    {
        $attack = AttackFactory::new()->create();
        $itemSnapshot = ItemSnapshotFactory::new()->create();
        $fantasyPower = round(rand(100, 5000)/100, 2);

        $attackSnapshot = $this->getDomainAction()->execute($attack, $itemSnapshot, $fantasyPower);

        $this->assertTrue(abs($attack->getCombatSpeed() - $attackSnapshot->combat_speed) < 0.01);
        $this->assertEquals($attack->name, $attackSnapshot->name);
        $this->assertEquals($attack->attacker_position_id, $attackSnapshot->attacker_position_id);
        $this->assertEquals($attack->target_position_id, $attackSnapshot->target_position_id);
        $this->assertEquals($attack->damage_type_id, $attackSnapshot->damage_type_id);
        $this->assertEquals($attack->target_priority_id, $attackSnapshot->target_priority_id);
        $this->assertEquals($attack->tier, $attackSnapshot->tier);
        $this->assertEquals($attack->targets_count, $attackSnapshot->targets_count);
    }

    /**
     * @test
     */
    public function the_attack_snapshot_will_belong_to_the_item_snapshot()
    {
        $attack = AttackFactory::new()->create();
        $itemSnapshot = ItemSnapshotFactory::new()->create();
        $fantasyPower = round(rand(100, 5000)/100, 2);

        $attackSnapshot = $this->getDomainAction()->execute($attack, $itemSnapshot, $fantasyPower);

        $this->assertEquals($attackSnapshot->attacker_id, $itemSnapshot->id);
        $this->assertEquals($attackSnapshot->attacker_type, ItemSnapshot::RELATION_MORPH_MAP_KEY);
    }

    /**
     * @test
     */
    public function it_will_build_a_snapshot_attack_with_the_expected_damage()
    {
        $attack = AttackFactory::new()->create();
        $itemSnapshot = ItemSnapshotFactory::new()->create();
        $fantasyPower = round(rand(100, 5000)/100, 2);

        /** @var CalculateCombatDamage $calculateDamage */
        $calculateDamage = app(CalculateCombatDamage::class);
        $damage = $calculateDamage->execute($attack, $fantasyPower);

        $attackSnapshot = $this->getDomainAction()->execute($attack, $itemSnapshot, $fantasyPower);

        $this->assertEquals($damage, $attackSnapshot->damage);
    }

    /**
     * @test
     */
    public function it_will_build_an_attack_snapshot_with_matching_resource_costs()
    {
        $attack = AttackFactory::new()->create(['tier' => 3]);
        $attackResourceCosts = $attack->getResourceCosts();
        $this->assertTrue($attackResourceCosts->isNotEmpty());

        $itemSnapshot = ItemSnapshotFactory::new()->create();
        $fantasyPower = round(rand(100, 5000)/100, 2);

        /** @var AttackSnapshot $attackSnapshot */
        $attackSnapshot = $this->getDomainAction()->execute($attack, $itemSnapshot, $fantasyPower);

        $snapshotResourceCosts = $attackSnapshot->resource_costs;
        $this->assertTrue($snapshotResourceCosts->isNotEmpty());

        $snapshotResourceCosts->each(function (ResourceCost $resourceCost) use ($attackResourceCosts) {
            $match = $attackResourceCosts->first(function (ResourceCost $attackResourceCost) use ($resourceCost) {
                if ($attackResourceCost instanceof FixedResourceCost) {
                    if (! $resourceCost instanceof FixedResourceCost) {
                        return false;
                    }
                    return $attackResourceCost->getAmount() === $resourceCost->getAmount()
                        && $attackResourceCost->getResourceName() === $resourceCost->getResourceName();
                } else {
                    /** @var PercentResourceCost $attackResourceCost */
                    if (! $resourceCost instanceof PercentResourceCost) {
                        return false;
                    }
                    return abs($attackResourceCost->getPercent() - $resourceCost->getPercent()) < PHP_FLOAT_EPSILON
                        && $attackResourceCost->getResourceName() === $resourceCost->getResourceName();
                }
            });
            $this->assertNotNull($match);
        });
    }

    /**
     * @test
     */
    public function it_will_build_an_attack_snapshot_with_higher_damage_with_a_higher_quality_item_snapshot()
    {
        /** @var ItemBase $swordBase */
        $swordBase = ItemBase::query()->where('name', '=', ItemBase::SWORD)->first();
        $swordTypes = $swordBase->itemTypes->sortByDesc('tier');
        $lowGradeSwordType = $swordTypes->last();

        $lowGradeSnapshot = ItemSnapshotFactory::new()->withItemTypeID($lowGradeSwordType->id)->create();

        $attack = AttackFactory::new()->create();
        $fantasyPower = round(rand(100, 5000)/100, 2);

        /** @var AttackSnapshot $attackSnapshot */
        $lowGradeAttackSnapshot = $this->getDomainAction()->execute($attack, $lowGradeSnapshot, $fantasyPower);

        $highGradeSwordType = $swordTypes->first();
        $highGradeSnapshot = ItemSnapshotFactory::new()
            ->withItemTypeID($highGradeSwordType->id)
            ->withHeroSnapshotID($lowGradeSnapshot->hero_snapshot_id)
            ->withMaterialID($lowGradeSnapshot->material_id)->create();

        $highGradeAttackSnapshot = $this->getDomainAction()->execute($attack, $highGradeSnapshot, $fantasyPower);

        $this->assertGreaterThan($lowGradeAttackSnapshot->damage, $highGradeAttackSnapshot->damage);
    }

}
