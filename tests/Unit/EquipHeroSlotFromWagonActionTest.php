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

    /**
     * @test
     */
    public function it_will_slot_multi_slot_item_if_slots_are_empty()
    {
        $twoHandAxeType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::TWO_HAND_AXE);
        })->first();

        $this->item->item_type_id = $twoHandAxeType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        /** @var Slot $primaryArmSlot */
        $primaryArmSlot = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::PRIMARY_ARM;
        });

        $this->equipAction->execute($this->hero, $primaryArmSlot, $this->item);

        $this->item = $this->item->fresh();
        $primaryArmSlot = $primaryArmSlot->fresh();
        $itemSlots = $this->item->slots;

        $this->assertEquals($this->item->getSlotsCount(), $itemSlots->count());
        $this->assertTrue($itemSlots->allBelongToHasSlots($this->hero));
        $this->assertEquals($this->item->id, $primaryArmSlot->item_id);
    }

    /**
     * @test
     */
    public function it_will_slot_single_item_slot_when_slot_is_full()
    {
        $neckLaceItemType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::NECKLACE);
        })->first();

        $this->item->item_type_id = $neckLaceItemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        /** @var Slot $neckSlot */
        $neckSlot = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::NECK;
        });

        /** @var Item $previouslyEquippedItem */
        $previouslyEquippedItem = factory(Item::class)->create([
            'item_type_id' => $neckLaceItemType->id
        ]);

        $neckSlot->item_id = $previouslyEquippedItem->id;
        $neckSlot->save();
        $neckSlot = $neckSlot->fresh();

        $this->equipAction->execute($this->hero, $neckSlot, $this->item);

        $this->item = $this->item->fresh();
        $neckSlot = $neckSlot->fresh();
        $itemSlots = $this->item->slots;

        $this->assertEquals($this->item->getSlotsCount(), $itemSlots->count());
        $this->assertTrue($itemSlots->allBelongToHasSlots($this->hero));
        $this->assertEquals($this->item->id, $neckSlot->item_id);

        $previouslyEquippedItem = $previouslyEquippedItem->fresh();
        $previouslyEquippedItemSlots = $previouslyEquippedItem->slots;
        $this->assertEquals($previouslyEquippedItem->getSlotsCount(), $previouslyEquippedItemSlots->count());
        $this->assertTrue($previouslyEquippedItemSlots->allBelongToHasSlots($this->squad));
    }

    /**
     * @test
     */
    public function it_will_slot_single_slot_item_if_slot_already_equipped_with_multi_slot_item()
    {
        $maceType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::MACE);
        })->first();

        $this->item->item_type_id = $maceType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        $psionicTwoHandType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::PSIONIC_TWO_HAND);
        })->first();

        /** @var Item $previouslyEquippedItem */
        $previouslyEquippedItem = factory(Item::class)->create([
            'item_type_id' => $psionicTwoHandType->id
        ]);

        $armSlots = $this->hero->slots->filter(function (Slot $slot) {
            return $slot->slotType->name === SlotType::PRIMARY_ARM || $slot->slotType->name === SlotType::OFF_ARM;
        });

        $previouslyEquippedItem->slots()->saveMany($armSlots);

        /** @var Slot $singleArmSlot */
        $singleArmSlot = $armSlots->shuffle()->first();
        $this->equipAction->execute($this->hero, $singleArmSlot->fresh(), $this->item);

        $this->item = $this->item->fresh();
        $singleArmSlot = $singleArmSlot->fresh();
        $itemSlots = $this->item->slots;

        $this->assertEquals($this->item->getSlotsCount(), $itemSlots->count());
        $this->assertTrue($itemSlots->allBelongToHasSlots($this->hero));
        $this->assertEquals($this->item->id, $singleArmSlot->item_id);

        $previouslyEquippedItem = $previouslyEquippedItem->fresh();
        $previouslyEquippedItemSlots = $previouslyEquippedItem->slots;

        $this->assertEquals($previouslyEquippedItem->getSlotsCount(), $previouslyEquippedItemSlots->count());
        $this->assertTrue($previouslyEquippedItemSlots->allBelongToHasSlots($this->squad));
    }

    /**
     * @test
     */
    public function it_will_slot_multi_slot_item_if_all_slots_are_full()
    {
        $crossbowType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::CROSSBOW);
        })->first();

        $this->item->item_type_id = $crossbowType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        $daggerType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::DAGGER);
        })->first();

        /** @var Item $previouslyEquippedItem1 */
        $previouslyEquippedItem1 = factory(Item::class)->create([
            'item_type_id' => $daggerType->id
        ]);

        /** @var Item $previouslyEquippedItem2 */
        $previouslyEquippedItem2 = factory(Item::class)->create([
            'item_type_id' => $daggerType->id
        ]);

        $armSlots = $this->hero->slots->filter(function (Slot $slot) {
            return $slot->slotType->name === SlotType::PRIMARY_ARM || $slot->slotType->name === SlotType::OFF_ARM;
        })->shuffle();

        $singleArmSlot1 = $armSlots->shift();
        $singleArmSlot2 = $armSlots->shift();
        $this->assertEquals(0, $armSlots->count());

        $previouslyEquippedItem1->slots()->save($singleArmSlot1);
        $previouslyEquippedItem2->slots()->save($singleArmSlot2);

        $this->equipAction->execute($this->hero, $singleArmSlot1->fresh(), $this->item);

        $this->item = $this->item->fresh();
        $singleArmSlot1 = $singleArmSlot1->fresh();
        $singleArmSlot2 = $singleArmSlot2->fresh();
        $itemSlots = $this->item->slots;

        $this->assertEquals($this->item->getSlotsCount(), $itemSlots->count());
        $this->assertTrue($itemSlots->allBelongToHasSlots($this->hero));
        $this->assertEquals($this->item->id, $singleArmSlot1->item_id);
        $this->assertEquals($this->item->id, $singleArmSlot2->item_id);

        $previouslyEquippedItem1 = $previouslyEquippedItem1->fresh();
        $previouslyEquippedItem1Slots = $previouslyEquippedItem1->slots;

        $this->assertEquals($previouslyEquippedItem1->getSlotsCount(), $previouslyEquippedItem1Slots->count());
        $this->assertTrue($previouslyEquippedItem1Slots->allBelongToHasSlots($this->squad));

        $previouslyEquippedItem2 = $previouslyEquippedItem1->fresh();
        $previouslyEquippedItem2Slots = $previouslyEquippedItem2->slots;

        $this->assertEquals($previouslyEquippedItem1->getSlotsCount(), $previouslyEquippedItem2Slots->count());
        $this->assertTrue($previouslyEquippedItem2Slots->allBelongToHasSlots($this->squad));
    }

    /**
     * @test
     */
    public function it_will_slot_multi_slot_item_if_only_other_slots_are_full()
    {
        $twoHandAxe = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::TWO_HAND_AXE);
        })->first();

        $this->item->item_type_id = $twoHandAxe->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $wagonSlots = $this->squad->slots->take($this->item->getSlotsCount());
        $this->item->slots()->saveMany($wagonSlots);

        $shieldType = ItemType::query()->whereHas('itemBase', function(Builder $builder) {
            return $builder->where('name', '=', ItemBase::SHIELD);
        })->first();

        /** @var Item $shield */
        $shield = factory(Item::class)->create([
            'item_type_id' => $shieldType->id
        ]);

        /** @var Slot $offArm */
        $offArm = $this->hero->slots->filter(function (Slot $slot) {
            return $slot->slotType->name === SlotType::OFF_ARM;
        })->first();

        /** @var Slot $primaryArm */
        $primaryArm = $this->hero->slots->filter(function (Slot $slot) {
            return $slot->slotType->name === SlotType::PRIMARY_ARM;
        })->first();

        $shield->slots()->save($offArm);

        $this->equipAction->execute($this->hero, $primaryArm, $this->item);

        $this->item = $this->item->fresh();
        $primaryArm = $primaryArm->fresh();
        $offArm = $offArm->fresh();
        $itemSlots = $this->item->slots;

        $this->assertEquals($this->item->getSlotsCount(), $itemSlots->count());
        $this->assertTrue($itemSlots->allBelongToHasSlots($this->hero));
        $this->assertEquals($this->item->id, $primaryArm->item_id);
        $this->assertEquals($this->item->id, $offArm->item_id);

        $shield = $shield->fresh();
        $shieldSlots = $shield->slots;

        $this->assertEquals($shield->getSlotsCount(), $shieldSlots->count());
        $this->assertTrue($shieldSlots->allBelongToHasSlots($this->squad));
    }
}
