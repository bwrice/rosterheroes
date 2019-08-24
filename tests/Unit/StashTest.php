<?php

namespace Tests\Unit;

use App\Domain\Models\Slot;
use App\Domain\Models\SlotType;
use App\Domain\Models\Stash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StashTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_universal_slot_type_id_as_preferred_slot_type_id_if_available()
    {
        $universalSlotTypeID = SlotType::where('name', '=', SlotType::UNIVERSAL)->first()->id;
        $slotTypeIDs[] = SlotType::where('name', '=', SlotType::RIGHT_ARM)->first()->id;
        $slotTypeIDs[] = $universalSlotTypeID;
        $slotTypeIDs[] = SlotType::where('name', '=', SlotType::TORSO)->first()->id;

        $this->assertEquals(3, count($slotTypeIDs));

        /** @var \App\Domain\Models\Stash $stash */
        $stash = factory(Stash::class)->create();

        $this->assertEquals($universalSlotTypeID, $stash->getPreferredSlotTypeID($slotTypeIDs));
    }

    /**
     * @test
     */
    public function it_will_still_return_a_slot_type_id_if_universal_type_is_not_available()
    {

        $slotTypeIDs[] = SlotType::where('name', '=', SlotType::RIGHT_ARM)->first()->id;
        $slotTypeIDs[] = SlotType::where('name', '=', SlotType::TORSO)->first()->id;

        $this->assertEquals(2, count($slotTypeIDs));

        /** @var Stash $stash */
        $stash = factory(Stash::class)->create();

        $this->assertTrue(in_array($stash->getPreferredSlotTypeID($slotTypeIDs), $slotTypeIDs));
    }

    /**
     * @test
     */
    public function it_will_create_slots()
    {
        /** @var \App\Domain\Models\Stash $stash */
        $stash = factory(Stash::class)->create();
        $slotTypeID = SlotType::where('name', '=', SlotType::UNIVERSAL)->first()->id;

        $slots = $stash->createEmptySlots(4, [$slotTypeID]);

        $this->assertEquals(4, $slots->count(), "Correct amount of slots created");

        $slots->each(function (Slot $slot) use ($slotTypeID) {
            $this->assertEquals($slotTypeID, $slot->slot_type_id, "Slot is the correct type");
        });
    }

    /**
     * @test
     */
    public function it_will_create_empty_slots_if_needed_when_getting_empty_slots()
    {
        /** @var \App\Domain\Models\Stash $stash */
        $stash = factory(Stash::class)->create();

        $this->assertEquals(0, $stash->slots()->count(), "Stash starts with NO slots");
        $slotTypeID = SlotType::where('name', '=', SlotType::UNIVERSAL)->first()->id;
        $slots = $stash->getEmptySlots(2, [$slotTypeID]);
        $this->assertEquals(2, $slots->count(), "Created the correct amount of slots");
        $slots->each(function(Slot $slot) use($slotTypeID) {
            $this->assertEquals($slotTypeID, $slot->slot_type_id, "Slots are the correct slot type");
        });
    }
}
