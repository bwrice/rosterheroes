<?php

namespace Tests\Feature;

use App\Admin\Content\Actions\SyncItemTypes;
use App\Admin\Content\Sources\AttackSource;
use App\Admin\Content\Sources\ItemTypeSource;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\TargetPriority;
use App\Facades\Content;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\ItemTypeFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class SyncItemTypesTest extends TestCase
{

    use DatabaseTransactions;


    /**
     * @return SyncItemTypes
     */
    protected function getDomainAction()
    {
        return app(SyncItemTypes::class);
    }

    /**
     * @test
     */
    public function it_will_add_a_missing_item_type()
    {
        $attacks = new Collection();

        $attackFactory = AttackFactory::new();

        $range = rand(1, 5);
        for ($i = 1; $i <= $range; $i++) {
            $attacks->push($attackFactory->create());
        }

        $itemTypesSource = ItemTypeSource::build(
            'Test ItemType ' . Str::random(),
            rand(1,6),
            ItemBase::query()->inRandomOrder()->first()->id,
            $attacks->pluck('uuid')->toArray()
        );

        $sourceUuid = $itemTypesSource->getUuid();

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect([$itemTypesSource]));

        $this->getDomainAction()->execute();

        /** @var ItemType $createdItemType */
        $createdItemType = ItemType::query()->where('uuid', '=', $sourceUuid)->first();

        $this->assertNotNull($createdItemType);
        $this->assertEquals($itemTypesSource->getName(), $createdItemType->name);
        $this->assertEquals($itemTypesSource->getItemBaseID(), $createdItemType->item_base_id);
        $this->assertEquals($itemTypesSource->getTier(), $createdItemType->tier);

        $attacks = $createdItemType->attacks;
        $attackUuids = $itemTypesSource->getAttackUuids();
        $this->assertEquals(count($attackUuids), $attacks->count());

        $attacks->each(function (Attack $attack) use ($attackUuids) {
            $this->assertTrue(in_array($attack->uuid, $attackUuids));
        });
    }

    /**
     * @test
     */
    public function it_will_update_a_changed_item_type()
    {
        $itemType = ItemTypeFactory::new()->create();

        $diffName = 'Test ItemType ' . Str::random();

        $updatedItemSource = ItemTypeSource::build(
            $diffName,
            $itemType->tier,
            $itemType->item_base_id,
            []
        );

        $uuid = (string) $itemType->uuid;
        $updatedItemSource->setUuid($uuid);

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect([$updatedItemSource]));

        $this->getDomainAction()->execute();

        /** @var ItemType $updatedItemType */
        $query = ItemType::query()->where('uuid', '=', $uuid);
        $this->assertEquals(1, $query->count());
        $updatedItemType = $query->first();
        $this->assertEquals($diffName, $updatedItemType->name);
    }

    /**
     * @test
     */
    public function it_will_sync_attacks_based_on_item_type_source()
    {
        $attackFactory = AttackFactory::new();

        $unchangedAttack = $attackFactory->create();
        $attackToRemove = $attackFactory->create();

        $itemType = ItemTypeFactory::new()->create();

        $itemType->attacks()->save($unchangedAttack);
        $itemType->attacks()->save($attackToRemove);

        $newlyAddedAttack = $attackFactory->create();

        $updatedAttackUuids = [
            $unchangedAttack->uuid,
            $newlyAddedAttack->uuid
        ];

        $updatedItemSource = ItemTypeSource::build(
            $itemType->name,
            $itemType->tier,
            $itemType->item_base_id,
            $updatedAttackUuids
        );

        $uuid = (string) $itemType->uuid;
        $updatedItemSource->setUuid($uuid);


        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect([$updatedItemSource]));

        $this->getDomainAction()->execute();

        /** @var ItemType $updatedItemType */
        $query = ItemType::query()->where('uuid', '=', $uuid);
        $this->assertEquals(1, $query->count());
        $updatedItemType = $query->first();

        $attacks = $updatedItemType->attacks;
        $this->assertEquals(2, $attacks->count());

        $attacks->each(function (Attack $attack) use ($updatedAttackUuids) {
            $this->assertTrue(in_array((string) $attack->uuid, $updatedAttackUuids));
        });
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_attacks_are_out_of_sync()
    {
        $unSyncedAttackSource = AttackSource::build(
            Str::random(),
            CombatPosition::query()->inRandomOrder()->first()->id,
            CombatPosition::query()->inRandomOrder()->first()->id,
            TargetPriority::query()->inRandomOrder()->first()->id,
            DamageType::query()->inRandomOrder()->first()->id,
            rand(1,6),
            rand(1,3)
        );


        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect([$unSyncedAttackSource]));

        $unSyncedItemSource = ItemTypeSource::build(
            'Test ItemType ' . Str::random(),
            rand(1,6),
            ItemBase::query()->inRandomOrder()->first()->id,
            []
        );

        $itemSourceUuid = $unSyncedItemSource->getUuid();

        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect([$unSyncedItemSource]));

        try {
            $this->getDomainAction()->execute();
        } catch (\Exception $exception) {
            $itemType = ItemType::query()->where('uuid', '=', $itemSourceUuid)->first();
            $this->assertNull($itemType);
            return;
        }

        $this->fail("Exception not thrown");
    }
}
