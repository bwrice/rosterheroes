<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildAttackSnapshot;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Models\ItemSnapshot;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
