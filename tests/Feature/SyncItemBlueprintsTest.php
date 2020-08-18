<?php

namespace Tests\Feature;

use App\Admin\Content\Actions\SyncItemBlueprints;
use App\Admin\Content\Sources\ItemBlueprintSource;
use App\Domain\Models\Enchantment;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\Material;
use App\Exceptions\SyncContentException;
use App\Facades\Content;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\ItemBlueprintFactory;
use App\Factories\Models\ItemTypeFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class SyncItemBlueprintsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return SyncItemBlueprints
     */
    protected function getDomainAction()
    {
        return app(SyncItemBlueprints::class);
    }

    /**
     * @test
     */
    public function it_will_add_a_missing_item_blueprint()
    {

        $attacks = new Collection();

        $attackFactory = AttackFactory::new();

        $range = rand(1, 5);
        for ($i = 1; $i <= $range; $i++) {
            $attacks->push($attackFactory->create());
        }

        $itemTypes = new Collection();

        $itemTypeFactory = ItemTypeFactory::new();

        for ($i = 1; $i <= $range; $i++) {
            $itemTypes->push($itemTypeFactory->create());
        }

        $uuid = (string) Str::uuid();

        $itemBlueprintSource = new ItemBlueprintSource(
            $uuid,
            'Test ItemBlueprint ' . Str::random(),
            'Test Description ' . Str::random(),
            rand(1,6),
            ItemBase::query()->inRandomOrder()->take(rand(1,3))->pluck('id')->toArray(),
            ItemClass::query()->inRandomOrder()->take(rand(1,3))->pluck('id')->toArray(),
            $itemTypes->pluck('uuid')->toArray(),
            $attacks->pluck('uuid')->toArray(),
            // TODO use factories once materials and enchantments are seeded trough content-syncing
            Material::query()->inRandomOrder()->take(rand(1,5))->pluck('uuid')->toArray(),
            Enchantment::query()->inRandomOrder()->take(rand(1,6))->pluck('uuid')->toArray()
        );


        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect([$itemBlueprintSource]));

        $this->getDomainAction()->execute();

        /** @var ItemBlueprint $createdItemBlueprint */
        $createdItemBlueprint = ItemBlueprint::query()->where('uuid', '=', $uuid)->first();

        $this->assertNotNull($createdItemBlueprint);
        $this->assertEquals($itemBlueprintSource->getItemName(), $createdItemBlueprint->item_name);
        $this->assertEquals($itemBlueprintSource->getDescription(), $createdItemBlueprint->description);
        $this->assertEquals($itemBlueprintSource->getEnchantmentPower(), $createdItemBlueprint->enchantment_power);
        $this->assertArrayElementsEqual($itemBlueprintSource->getItemBases(), $createdItemBlueprint->itemBases()->pluck('id')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getItemClasses(), $createdItemBlueprint->itemClasses()->pluck('id')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getItemTypes(), $createdItemBlueprint->itemTypes()->pluck('uuid')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getAttacks(), $createdItemBlueprint->attacks()->pluck('uuid')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getMaterials(), $createdItemBlueprint->materials()->pluck('uuid')->toArray());
        $this->assertArrayElementsEqual($itemBlueprintSource->getEnchantments(), $createdItemBlueprint->enchantments()->pluck('uuid')->toArray());
    }

    /**
     * @test
     */
    public function it_will_update_a_changed_item_blueprint()
    {

        $itemBlueprint = ItemBlueprintFactory::new()->create();

        $diffItemName = 'Test ItemBlueprint ' . Str::random();

        $updatedItemSource = new ItemBlueprintSource(
            $itemBlueprint->uuid,
            $diffItemName,
            $itemBlueprint->description,
            $itemBlueprint->enchantment_power,
            [],
            [],
            [],
            [],
            [],
            []
        );

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect([$updatedItemSource]));

        $this->getDomainAction()->execute();

        /** @var ItemBlueprint $itemBlueprint */
        $query = ItemBlueprint::query()->where('uuid', '=', $itemBlueprint->uuid);
        $this->assertEquals(1, $query->count());
        /** @var ItemBlueprint $updatedItemBlueprint */
        $updatedItemBlueprint = $query->first();
        $this->assertEquals($diffItemName, $updatedItemBlueprint->item_name);
    }


    /**
     * @test
     */
    public function it_will_sync_attacks_based_on_item_blueprint_source()
    {
        $attackFactory = AttackFactory::new();

        $unchangedAttack = $attackFactory->create();
        $attackToRemove = $attackFactory->create();

        $itemBlueprint = ItemBlueprintFactory::new()->create();

        $itemBlueprint->attacks()->save($unchangedAttack);
        $itemBlueprint->attacks()->save($attackToRemove);

        $newlyAddedAttack = $attackFactory->create();

        $updatedAttackUuids = [
            $unchangedAttack->uuid,
            $newlyAddedAttack->uuid
        ];

        $updatedItemSource = new ItemBlueprintSource(
            $itemBlueprint->uuid,
            $itemBlueprint->item_name,
            $itemBlueprint->description,
            $itemBlueprint->enchantment_power,
            $itemBlueprint->itemBases()->pluck('id')->toArray(),
            $itemBlueprint->itemClasses()->pluck('id')->toArray(),
            $itemBlueprint->itemTypes()->pluck('uuid')->toArray(),
            $updatedAttackUuids,
            $itemBlueprint->materials()->pluck('uuid')->toArray(),
            $itemBlueprint->enchantments()->pluck('uuid')->toArray()
        );

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect([$updatedItemSource]));

        $this->getDomainAction()->execute();

        /** @var ItemBlueprint $updatedItemBlueprint */
        $query = ItemBlueprint::query()->where('uuid', '=', $itemBlueprint->uuid);
        $this->assertEquals(1, $query->count());
        $updatedItemBlueprint = $query->first();

        $attacks = $updatedItemBlueprint->attacks;
        $this->assertEquals(2, $attacks->count());

        $this->assertArrayElementsEqual($updatedItemSource->getAttacks(), $attacks->pluck('uuid')->toArray());
    }
    /**
     * @test
     */
    public function it_will_sync_item_types_based_on_item_blueprint_source()
    {
        $itemTypeFactory = ItemTypeFactory::new();

        $unchangedItemType = $itemTypeFactory->create();
        $itemTypeToRemove = $itemTypeFactory->create();

        $itemBlueprint = ItemBlueprintFactory::new()->create();

        $itemBlueprint->itemTypes()->save($unchangedItemType);
        $itemBlueprint->itemTypes()->save($itemTypeToRemove);

        $newlyAddedItemType = $itemTypeFactory->create();

        $updatedItemTypes = [
            $unchangedItemType->uuid,
            $newlyAddedItemType->uuid
        ];

        $updatedItemSource = new ItemBlueprintSource(
            $itemBlueprint->uuid,
            $itemBlueprint->item_name,
            $itemBlueprint->description,
            $itemBlueprint->enchantment_power,
            $itemBlueprint->itemBases()->pluck('id')->toArray(),
            $itemBlueprint->itemClasses()->pluck('id')->toArray(),
            $updatedItemTypes,
            $itemBlueprint->attacks()->pluck('uuid')->toArray(),
            $itemBlueprint->materials()->pluck('uuid')->toArray(),
            $itemBlueprint->enchantments()->pluck('uuid')->toArray()
        );

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect([$updatedItemSource]));

        $this->getDomainAction()->execute();

        /** @var ItemBlueprint $updatedItemBlueprint */
        $query = ItemBlueprint::query()->where('uuid', '=', $itemBlueprint->uuid);
        $this->assertEquals(1, $query->count());
        $updatedItemBlueprint = $query->first();

        $itemTypes = $updatedItemBlueprint->itemTypes;
        $this->assertEquals(2, $itemTypes->count());

        $this->assertArrayElementsEqual($updatedItemSource->getItemTypes(), $itemTypes->pluck('uuid')->toArray());
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_when_syncing_item_blueprints_if_attacks_are_out_of_sync()
    {
        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect(['anything']));
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect());

        try {
            $this->getDomainAction()->execute();
        } catch (SyncContentException $syncContentException) {
            $this->assertEquals(SyncContentException::CODE_ATTACKS_NOT_SYNCED, $syncContentException->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_when_syncing_item_blueprints_if_item_types_are_out_of_sync()
    {
        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect(['anything']));

        try {
            $this->getDomainAction()->execute();
        } catch (SyncContentException $syncContentException) {
            $this->assertEquals(SyncContentException::CODE_ITEM_TYPES_NOT_SYNCED, $syncContentException->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_sync_item_blueprints_while_returning_sources_that_failed()
    {

        $unknownItemTypeUuid = (string) Str::uuid();
        $failedSource = new ItemBlueprintSource(
            Str::uuid(),
            'Test ItemBlueprint ' . Str::random(),
            'Test Description ' . Str::random(),
            rand(1,6),
            [],
            [],
            [$unknownItemTypeUuid],
            [],
            [],
            []
        );

        $successfulSource = new ItemBlueprintSource(
            Str::uuid(),
            'Test ItemBlueprint ' . Str::random(),
            'Test Description ' . Str::random(),
            rand(1,6),
            [],
            [],
            [],
            [],
            [],
            []
        );

        Content::partialMock()->shouldReceive('unSyncedAttacks')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemTypes')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect([$failedSource, $successfulSource]));

        $unSyncedSources = $this->getDomainAction()->execute();

        $this->assertEquals(1, $unSyncedSources->count());
        $this->assertEquals($failedSource->getUuid(), $unSyncedSources->first()['source']->getUuid());

        $this->assertNull(ItemBlueprint::query()->where('uuid', '=', $failedSource->getUuid())->first());
        $this->assertNotNull(ItemBlueprint::query()->where('uuid', '=', $successfulSource->getUuid())->first());
    }
}
