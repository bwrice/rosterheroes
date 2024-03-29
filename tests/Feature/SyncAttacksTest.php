<?php

namespace Tests\Feature;

use App\Admin\Content\Actions\SyncAttacks;
use App\Admin\Content\Sources\AttackSource;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Facades\Content;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class SyncAttacksTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return SyncAttacks
     */
    protected function getDomainAction()
    {
        return app(SyncAttacks::class);
    }

    /**
     * @test
     */
    public function it_will_add_a_missing_attack_to_the_db()
    {

        $newAttackSource = new AttackSource(
            (string) Str::uuid(),
            'Test Attack ' . Str::random(8),
            CombatPosition::query()->inRandomOrder()->first()->id,
            CombatPosition::query()->inRandomOrder()->first()->id,
            TargetPriority::query()->inRandomOrder()->first()->id,
            DamageType::query()->inRandomOrder()->first()->id,
            rand(1,6),
            rand(1,3)
        );

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect([$newAttackSource]));

        $this->getDomainAction()->execute();


        /** @var Attack $newAttack */
        $newAttack = Attack::query()->where('uuid', '=', $newAttackSource->getUuid())->first();

        $this->assertNotNull($newAttack);
        $this->assertEquals($newAttack->name, $newAttackSource->getName());
        $this->assertEquals($newAttack->attacker_position_id, $newAttackSource->getAttackerPositionID());
        $this->assertEquals($newAttack->target_position_id, $newAttackSource->getTargetPositionID());
        $this->assertEquals($newAttack->target_priority_id, $newAttackSource->getTargetPriorityID());
        $this->assertEquals($newAttack->damage_type_id, $newAttackSource->getDamageTypeID());
        $this->assertEquals($newAttack->tier, $newAttackSource->getTier());
        $this->assertEquals($newAttack->targets_count, $newAttackSource->getTargetsCount());
    }

    /**
     * @test
     */
    public function it_will_update_a_changed_attack()
    {
        $newAttackSource = new AttackSource(
            (string) Str::uuid(),
            'Test Attack ' . Str::random(8),
            CombatPosition::query()->inRandomOrder()->first()->id,
            CombatPosition::query()->inRandomOrder()->first()->id,
            TargetPriority::query()->inRandomOrder()->first()->id,
            DamageType::query()->inRandomOrder()->first()->id,
            rand(1,6),
            rand(1,3)
        );

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect([$newAttackSource]));

        // Create the initial new attack
        $this->getDomainAction()->execute();

        // Change the new attack's name
        $newAttackSource->setName('Test Attack ' . Str::random(8));

        // Execute action again to update name
        $this->getDomainAction()->execute();

        /** @var Attack $updatedAttack */
        $updatedAttack = Attack::query()->where('uuid', '=', $newAttackSource->getUuid())->first();
        $this->assertEquals($updatedAttack->name, $newAttackSource->getName());
    }
}
