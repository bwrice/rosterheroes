<?php

namespace Tests\Unit;

use App\Domain\Actions\EmptyHeroSlotAction;
use App\Domain\Actions\EquipHeroSlotFromWagonAction;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Exceptions\SlottingException;
use App\Nova\Hero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EquipHeroSlotFromWagonActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $item;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var EquipHeroSlotFromWagonAction */
    protected $equipAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->item = factory(Item::class)->create();
        $this->squad = factory(Squad::class)->states('with-slots')->create();

        $this->hero = factory(\App\Domain\Models\Hero::class)->states('with-slots', 'with-measurables')->create();

        factory(HeroPost::class)->create([
            'hero_id' => $this->hero->id,
            'squad_id' => $this->squad->id
        ]);

        $this->equipAction = app(EquipHeroSlotFromWagonAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_slot_does_not_belong_to_the_hero()
    {
        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        $slot = factory(Slot::class)->create();
        try {
            $this->equipAction->execute($this->hero, $slot, $this->item->fresh());
        } catch (SlottingException $exception) {
            $this->assertEquals( SlottingException::CODE_INVALID_SLOT_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_doesnt_belong_to_the_heroes_squad()
    {
        $glovesType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::GLOVES);
        })->first();

        $this->item->item_type_id = $glovesType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $handsSlot = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::HANDS;
        });

        // assert item is not in squad/wagon
        $this->assertEquals(0, $this->item->slots->count());
        try {
            $this->equipAction->execute($this->hero, $handsSlot, $this->item);
        } catch (SlottingException $exception) {
            $this->assertEquals(SlottingException::CODE_INVALID_ITEM_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_slot_type_is_invalid()
    {
        $necklaceType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::NECKLACE);
        })->first();

        $this->item->item_type_id = $necklaceType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);
        $this->item = $this->item->fresh();

        $torsoSlot = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::TORSO;
        });

        try {
            $this->equipAction->execute($this->hero, $torsoSlot, $this->item);
        } catch (SlottingException $exception) {
            $this->assertEquals(SlottingException::CODE_INVALID_SLOT_TYPE, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_slot_single_slot_item_if_slot_is_empty()
    {
        $bootsItemType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::BOOTS);
        })->first();

        $this->item->item_type_id = $bootsItemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        /** @var Slot $feetSlot */
        $feetSlot = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::FEET;
        });

        $this->equipAction->execute($this->hero, $feetSlot, $this->item);

        $this->item = $this->item->fresh();
        $feetSlot = $feetSlot->fresh();
        $itemSlots = $this->item->slots;

        $this->assertEquals($this->item->getSlotsCount(), $itemSlots->count());
        $this->assertTrue($itemSlots->allBelongToHasSlots($this->hero));
        $this->assertEquals($this->item->id, $feetSlot->item_id);
    }

    public function it_will_slot_multi_slot_item_if_slots_are_empty()
    {

    }

    public function it_will_slot_single_item_slot_when_slot_is_full()
    {

    }

    public function it_will_slot_multi_slot_item_if_current_slot_is_full()
    {

    }

    public function it_will_slot_multi_slot_item_if_all_slots_are_full()
    {

    }

    public function it_will_slot_multi_slot_item_if_only_other_slots_are_full()
    {

    }
}
