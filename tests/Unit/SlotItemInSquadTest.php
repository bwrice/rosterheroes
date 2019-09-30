<?php

namespace Tests\Unit;

use App\Domain\Actions\SlotItemInWagonAction;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\StoreHouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotItemInSquadTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $item;

    /** @var Item */
    protected $itemTwo;

    /** @var Item */
    protected $itemThree;

    /** @var Squad */
    protected $squad;

    /** @var StoreHouse */
    protected $storeHouse;

    /** @var SlotItemInWagonAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->item = factory(Item::class)->create();
        $this->itemTwo = factory(Item::class)->create();
        $this->itemThree = factory(Item::class)->create();
        $this->squad = factory(Squad::class)->states('with-slots')->create();
        $this->storeHouse = factory(StoreHouse::class)->states('with-slots')->create([
            'squad_id' => $this->squad->id,
            'province_id' => $this->squad->province_id
        ]);
        $this->domainAction = app(SlotItemInWagonAction::class);
    }

    /**
     * @test
     */
    public function it_will_slot_an_item_on_an_empty_squad()
    {
        $itemType = ItemType::query()->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $this->domainAction->execute($this->squad, $this->item);

        $this->item = $this->item->fresh();
        $filledSlots = $this->item->slots;
        $this->assertEquals($this->item->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $hasSlots->id);
        });
    }

    /**
     * @test
     */
    public function it_will_stash_an_item_in_an_available_storehouse_when_full()
    {
        $itemType = ItemType::query()->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $this->itemTwo->item_type_id = $itemType->id;
        $this->itemTwo->save();
        $this->itemTwo = $this->itemTwo->fresh();

        $squadSlotsCount = $this->squad->slots()->count();
        $this->squad->slots()->take($squadSlotsCount - $this->item->getSlotsCount())->delete();

        $this->domainAction->execute($this->squad, $this->item);
        $this->squad = $this->squad->fresh();
        $this->domainAction->execute($this->squad, $this->itemTwo);

        $this->item = $this->item->fresh();
        $filledSlots = $this->item->slots;
        $this->assertEquals($this->item->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $hasSlots->id);
        });

        $localStoreHouse = $this->squad->fresh()->getLocalStoreHouse();

        $this->itemTwo = $this->itemTwo->fresh();
        $filledSlots = $this->itemTwo->slots;
        $this->assertEquals($this->itemTwo->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) use ($localStoreHouse) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(StoreHouse::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $localStoreHouse->id);
        });
    }

    /**
     * @test
     */
    public function it_will_stash_an_item_when_full_an_no_available_storehouse()
    {
        // move the store-house so it's not local to the squad
        $squadProvinceID = $this->squad->province_id;
        $newStoreHouseProvinceID = $squadProvinceID > 1 ? $squadProvinceID - 1 : $squadProvinceID + 1;
        $this->storeHouse->province_id = $newStoreHouseProvinceID;
        $this->storeHouse->save();

        $itemType = ItemType::query()->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $this->itemTwo->item_type_id = $itemType->id;
        $this->itemTwo->save();
        $this->itemTwo = $this->itemTwo->fresh();

        $squadSlotsCount = $this->squad->slots()->count();
        $this->squad->slots()->take($squadSlotsCount - $this->item->getSlotsCount())->delete();

        $this->domainAction->execute($this->squad, $this->item);
        $this->squad = $this->squad->fresh();
        $this->domainAction->execute($this->squad, $this->itemTwo);

        $this->item = $this->item->fresh();
        $filledSlots = $this->item->slots;
        $this->assertEquals($this->item->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $this->squad->id);
        });

        $localStash = $this->squad->getLocalStash();

        $this->itemTwo = $this->itemTwo->fresh();
        $filledSlots = $this->itemTwo->slots;
        $this->assertEquals($this->itemTwo->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) use ($localStash) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Stash::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $localStash->id);
        });
    }

    /**
     * @test
     */
    public function it_will_stash_an_item_if_both_the_squad_and_local_storehouse_is_full()
    {
        $itemType = ItemType::query()->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $this->itemTwo->item_type_id = $itemType->id;
        $this->itemTwo->save();
        $this->itemTwo = $this->itemTwo->fresh();

        $this->itemThree->item_type_id = $itemType->id;
        $this->itemThree->save();
        $this->itemThree = $this->itemThree->fresh();

        $squadSlotsCount = $this->squad->slots()->count();
        $this->squad->slots()->take($squadSlotsCount - $this->item->getSlotsCount())->delete();

        $storeHouseSlots = $this->storeHouse->slots()->count();
        $this->storeHouse->slots()->take($storeHouseSlots - $this->item->getSlotsCount())->delete();

        $this->domainAction->execute($this->squad, $this->item);
        $this->squad = $this->squad->fresh();
        $this->domainAction->execute($this->squad, $this->itemTwo);
        $this->squad = $this->squad->fresh();
        $this->domainAction->execute($this->squad, $this->itemThree);

        $this->item = $this->item->fresh();
        $filledSlots = $this->item->slots;
        $this->assertEquals($this->item->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $this->squad->id);
        });

        $this->itemTwo = $this->itemTwo->fresh();
        $filledSlots = $this->itemTwo->slots;
        $this->assertEquals($this->itemTwo->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot){
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(StoreHouse::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $this->storeHouse->id);
        });

        $localStash = $this->squad->getLocalStash();

        $this->itemThree = $this->itemThree->fresh();
        $filledSlots = $this->itemThree->slots;
        $this->assertEquals($this->itemThree->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) use ($localStash) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Stash::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $localStash->id);
        });
    }


    /**
     * @test
     */
    public function it_will_not_create_a_duplicate_stash()
    {
        // move the store-house so it's not local to the squad
        $squadProvinceID = $this->squad->province_id;
        $newStoreHouseProvinceID = $squadProvinceID > 1 ? $squadProvinceID - 1 : $squadProvinceID + 1;
        $this->storeHouse->province_id = $newStoreHouseProvinceID;
        $this->storeHouse->save();

        $itemType = ItemType::query()->inRandomOrder()->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $this->itemTwo->item_type_id = $itemType->id;
        $this->itemTwo->save();
        $this->itemTwo = $this->itemTwo->fresh();

        $this->itemThree->item_type_id = $itemType->id;
        $this->itemThree->save();
        $this->itemThree = $this->itemThree->fresh();

        $squadSlotsCount = $this->squad->slots()->count();
        $this->squad->slots()->take($squadSlotsCount - $this->item->getSlotsCount())->delete();

        $this->domainAction->execute($this->squad, $this->item);
        $this->squad = $this->squad->fresh();
        $this->domainAction->execute($this->squad, $this->itemTwo);
        $this->squad = $this->squad->fresh();
        $this->domainAction->execute($this->squad, $this->itemThree);

        $this->item = $this->item->fresh();
        $filledSlots = $this->item->slots;
        $this->assertEquals($this->item->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $this->squad->id);
        });

        $localStash = $this->squad->getLocalStash();

        $this->itemTwo = $this->itemTwo->fresh();
        $filledSlots = $this->itemTwo->slots;
        $this->assertEquals($this->itemTwo->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) use ($localStash) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Stash::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $localStash->id);
        });

        $localStash = $this->squad->getLocalStash();

        $this->itemThree = $this->itemThree->fresh();
        $filledSlots = $this->itemThree->slots;
        $this->assertEquals($this->itemThree->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) use ($localStash) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Stash::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $localStash->id);
        });
    }
}
