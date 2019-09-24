<?php

namespace Tests\Unit;

use App\Domain\Actions\SlotItemInSquad;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
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

    /** @var Squad */
    protected $squad;

    /** @var SlotItemInSquad */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->item = factory(Item::class)->create();
        $this->itemTwo = factory(Item::class)->create();
        $this->squad = factory(Squad::class)->states('with-slots')->create();
        $this->domainAction = app(SlotItemInSquad::class);
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
    public function it_will_stash_an_item_when_full()
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

        $localStash = $this->squad->getLocalStash();
        $this->assertNotNull($localStash);

        $this->itemTwo = $this->itemTwo->fresh();
        $filledSlots = $this->itemTwo->slots;
        $this->assertEquals($this->itemTwo->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) use ($localStash) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Stash::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $localStash->id);
        });
    }
}
