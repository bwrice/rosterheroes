<?php

namespace Tests\Unit;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Slot;
use App\Domain\Collections\SlotCollection;
use App\Actions\Slotter;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotterTest extends TestCase
{
    use DatabaseTransactions;

    protected $squadName;

    /** @var User */
    protected $user;
    /** @var Squad */
    protected $squad;
    /** @var Slotter $slotter */
    protected $slotter;

    /**
     * @test
     * @dataProvider provides_it_can_slot_items_on_empty_heroes
     *
     * @param $itemBase
     * @throws \Exception
     */
    public function it_can_slot_items_on_empty_heroes($itemBase)
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();

        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::where('name', $itemBase)->first();

        /** @var \App\Domain\Models\ItemBlueprint $blueprint */
        $blueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $item = $blueprint->generate();

        /** @var Slotter $slotter */
        $slotter = app()->make(Slotter::class);
        $slotter->slot($hero, $item);

        $this->assertEquals($itemBase->getSlotsCount(), $item->slots->count(), "Item takes up the correct amount of slots");
        $item->slots->each(function (Slot $slot) use ($item) {
            $this->assertEquals($slot->slottable_id, $item->id);
            $this->assertEquals($slot->slottable_type, Item::RELATION_MORPH_MAP);
        });

        $this->assertEquals($itemBase->getSlotsCount(), $hero->slots->filled()->count(), "Hero has correct amount of slots filled");
    }

    public function provides_it_can_slot_items_on_empty_heroes()
    {
        return [
            ItemBase::DAGGER => [
                'item_base' => ItemBase::DAGGER,
            ],
            ItemBase::SWORD => [
                'item_base' => ItemBase::SWORD,
            ],
            ItemBase::AXE => [
                'item_base' => ItemBase::AXE,
            ],
            ItemBase::MACE => [
                'item_base' => ItemBase::MACE,
            ],
            ItemBase::BOW => [
                'item_base' => ItemBase::BOW,
            ],
            ItemBase::CROSSBOW => [
                'item_base' => ItemBase::CROSSBOW,
            ],
            ItemBase::THROWING_WEAPON => [
                'item_base' => ItemBase::THROWING_WEAPON,
            ],
            ItemBase::POLE_ARM => [
                'item_base' => ItemBase::POLE_ARM,
            ],
            ItemBase::TWO_HAND_SWORD => [
                'item_base' => ItemBase::TWO_HAND_SWORD,
            ],
            ItemBase::TWO_HAND_AXE => [
                'item_base' => ItemBase::TWO_HAND_AXE,
            ],
            ItemBase::WAND => [
                'item_base' => ItemBase::WAND,
            ],
            ItemBase::ORB => [
                'item_base' => ItemBase::ORB,
            ],
            ItemBase::STAFF => [
                'item_base' => ItemBase::STAFF,
            ],
            ItemBase::PSIONIC_ONE_HAND => [
                'item_base' => ItemBase::PSIONIC_ONE_HAND,
            ],
            ItemBase::SHIELD => [
                'item_base' => ItemBase::SHIELD,
            ],
            ItemBase::PSIONIC_SHIELD => [
                'item_base' => ItemBase::PSIONIC_SHIELD,
            ],
            ItemBase::HELMET => [
                'item_base' => ItemBase::HELMET,
            ],
            ItemBase::CAP => [
                'item_base' => ItemBase::CAP,
            ],
            ItemBase::EYE_WEAR => [
                'item_base' => ItemBase::EYE_WEAR,
            ],
            ItemBase::HEAVY_ARMOR => [
                'item_base' => ItemBase::HEAVY_ARMOR,
            ],
            ItemBase::LIGHT_ARMOR => [
                'item_base' => ItemBase::LIGHT_ARMOR,
            ],
            ItemBase::ROBES => [
                'item_base' => ItemBase::ROBES,
            ],
            ItemBase::GLOVES => [
                'item_base' => ItemBase::GLOVES,
            ],
            ItemBase::GAUNTLETS => [
                'item_base' => ItemBase::GAUNTLETS,
            ],
            ItemBase::SHOES => [
                'item_base' => ItemBase::SHOES,
            ],
            ItemBase::BOOTS => [
                'item_base' => ItemBase::BOOTS,
            ],
            ItemBase::BELT => [
                'item_base' => ItemBase::BELT,
            ],
            ItemBase::SASH => [
                'item_base' => ItemBase::SASH,
            ],
            ItemBase::NECKLACE => [
                'item_base' => ItemBase::NECKLACE,
            ],
            ItemBase::BRACELET => [
                'item_base' => ItemBase::BRACELET,
            ],
            ItemBase::RING => [
                'item_base' => ItemBase::RING,
            ],
            ItemBase::CROWN => [
                'item_base' => ItemBase::CROWN,
            ]
        ];
    }


    /**
     * @test
     * @dataProvider provides_it_can_slot_and_replace_items_on_heroes_and_move_them_to_squad
     *
     * @param $firstItemBaseName
     * @param $secondItemBaseName
     * @throws \Exception
     */
    public function it_can_slot_and_replace_items_on_heroes_and_move_them_to_squad($firstItemBaseName, $secondItemBaseName)
    {

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create();
        $heroPost->squad->addSlots();

        /** @var Hero $hero */
        $hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();
        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        /** @var ItemBase $firstItemBase */
        $firstItemBase = ItemBase::where('name', $firstItemBaseName)->first();

        /** @var ItemBlueprint $firstBlueprint */
        $firstBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $firstItemBase->id
        ]);

        $itemOne = $firstBlueprint->generate();

        /** @var \App\Actions\Slotter $slotter */
        $slotter = app()->make(Slotter::class);
        $slotter->slot($hero, $itemOne);

        /** @var \App\Domain\Models\ItemBase $firstItemBase */
        $secondItemBase = ItemBase::where('name', $secondItemBaseName)->first();

        $secondBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $secondItemBase->id
        ]);


        $itemTwo = $secondBlueprint->generate();

        $slotter->slot($hero, $itemTwo);

        $itemOne->slots->each(function (Slot $slot) use ($heroPost) {
            $this->assertEquals($heroPost->squad->id, $slot->has_slots_id, "First item now slotted in squad");
        });

        $itemTwo->slots->each(function (Slot $slot) use ($hero) {
            $this->assertEquals($hero->id, $slot->has_slots_id, "Second item now slotted in hero");
        });
    }

    public function provides_it_can_slot_and_replace_items_on_heroes_and_move_them_to_squad()
    {
        return [
            'helmet replaced with helmet' => [
                'first_item_base_name' => ItemBase::HELMET,
                'second_item_base_name' => ItemBase::HELMET
                ],
            'bow replaced with two-hand sword' => [
                'first_item_base_name' => ItemBase::BOW,
                'second_item_base_name' => ItemBase::TWO_HAND_SWORD
                ],
            'crossbow replaced with wand' => [
                'first_item_base_name' => ItemBase::CROSSBOW,
                'second_item_base_name' => ItemBase::WAND
                ],
            'dagger replaced replaced with staff' => [
                'first_item_base_name' => ItemBase::DAGGER,
                'second_item_base_name' => ItemBase::STAFF
                ],
            'orb replaced replaced with mace' => [
                'first_item_base_name' => ItemBase::ORB,
                'second_item_base_name' => ItemBase::MACE
                ],
            'shield replaced replaced with shield' => [
                'first_item_base_name' => ItemBase::SHIELD,
                'second_item_base_name' => ItemBase::SHIELD
                ]
        ];
    }

    /**
     * @test
     * @dataProvider provides_it_can_slot_single_slot_items_with_multi_slot_options_and_only_move_one_to_the_squad_if_possible
     * @param $firstItemBaseName
     * @param $secondItemBaseName
     * @param $thirdItemBaseName
     * @throws \Exception
     */
    public function it_can_slot_single_slot_items_with_multi_slot_options_and_only_move_one_to_the_squad_if_possible($firstItemBaseName, $secondItemBaseName, $thirdItemBaseName)
    {
        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create();
        $heroPost->squad->addSlots();

        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();
        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        /** @var ItemBase $itemBase */
        $itemBase = ItemBase::where('name', $firstItemBaseName)->first();

        /** @var \App\Domain\Models\ItemBlueprint $itemBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $itemOne = $itemBlueprint->generate();

        /** @var \App\Actions\Slotter $slotter */
        $slotter = app()->make(Slotter::class);
        $slotter->slot($hero, $itemOne);

        /** @var \App\Domain\Models\ItemBase $firstItemBase */
        $itemBase = ItemBase::where('name', $secondItemBaseName)->first();

        /** @var \App\Domain\Models\ItemBlueprint $firstBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $itemTwo = $itemBlueprint->generate();

        $slotter->slot($hero, $itemTwo);

        /** @var ItemBase $firstItemBase */
        $itemBase = ItemBase::where('name', $thirdItemBaseName)->first();

        /** @var \App\Domain\Models\ItemBlueprint $firstBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $itemThree = $itemBlueprint->generate();

        $slotter->slot($hero, $itemThree);

        $this->assertEquals(1, $itemOne->slots->count(), "Single slot item only taking up one slot");
        $this->assertEquals(1, $itemTwo->slots->count(), "Single slot item only taking up one slot");
        $this->assertEquals(1, $itemThree->slots->count(), "Single slot item only taking up one slot");

        $slotOne = $itemOne->slots->first();
        $slotTwo = $itemTwo->slots->first();
        $slotThree = $itemThree->slots->first();


        $this->assertEquals($heroPost->squad->id, $slotOne->has_slots_id, "First item now slotted in squad");
        $this->assertEquals($hero->id, $slotTwo->has_slots_id, "Second item is still slotted in hero");
        $this->assertEquals($hero->id, $slotThree->has_slots_id, "Third item now slotted in hero");
    }

    public function provides_it_can_slot_single_slot_items_with_multi_slot_options_and_only_move_one_to_the_squad_if_possible()
    {
        return [
            'dagger and sword slotted and then axe slotted' => [
                'first_item_base_name' => ItemBase::DAGGER,
                'second_item_base_name' => ItemBase::SWORD,
                'third_item_base_name' => ItemBase::AXE
            ],
            'two rings slotted and then another ring' => [
                'first_item_base_name' => ItemBase::RING,
                'second_item_base_name' => ItemBase::RING,
                'third_item_base_name' => ItemBase::RING
            ],
            'two bracelets slotted and then another bracelet' => [
                'first_item_base_name' => ItemBase::BRACELET,
                'second_item_base_name' => ItemBase::BRACELET,
                'third_item_base_name' => ItemBase::BRACELET
            ]
        ];
    }

    /**
     * @test
     * @dataProvider provides_slotting_to_a_full_squad_will_stash_the_item_if_no_store_house_is_available
     */
    public function slotting_to_a_full_squad_will_stash_the_item_if_no_store_house_is_available($slotsToKeepCount, $firstItemBaseName, $secondItemBaseName)
    {
        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create();
        $squad = $heroPost->squad;
        $squad->addSlots();

        /** @var Hero $hero */
        $hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();
        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        // delete all slots but the slots to keep
        $squadSlotCount = $squad->slots()->count();
        $squad->slots()->take($squadSlotCount - $slotsToKeepCount)->delete();
        $this->assertGreaterThan(0, $squad->slots()->count());

        $itemBase = ItemBase::where('name', '=', $firstItemBaseName)->first();

        /** @var \App\Domain\Models\ItemBlueprint $itemBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $firstItem = $itemBlueprint->generate();

        // delete local storehouse if it exists
        if($squad->getLocalStoreHouse()) {
            $squad->getLocalStoreHouse()->delete();
        }
        $squad = $squad->fresh();

        /** @var \App\Actions\Slotter $slotter */
        $slotter = app()->make(Slotter::class);
        $slotter->slot($squad, $firstItem);

        $firstItemSlots = $firstItem->slots()->get();
        $this->assertEquals($firstItem->getSlotsCount(), $firstItemSlots->count(), "First item is slotted");

        $firstItemSlots->each(function (Slot $slot) use ($squad) {
            $this->assertEquals($squad->id, $slot->has_slots_id, "item slots belong to squad");
        });

        $itemBase = ItemBase::where('name', '=', $secondItemBaseName)->first();

        /** @var \App\Domain\Models\ItemBlueprint $itemBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $secondItem = $itemBlueprint->generate();
        $slotter->slot($squad, $secondItem);

        $secondItemSlots = $secondItem->slots()->get();
        $this->assertEquals($secondItem->getSlotsCount(), $secondItemSlots->count(), "Second item is slotted");

        $secondItemSlots->each(function (Slot $slot) use ($squad) {
            $this->assertEquals($squad->id, $slot->has_slots_id, "item slots belong to squad");
        });

        // Now verify first item is still slotted, but slotted in stash
        $firstItemSlots = $firstItem->slots()->get();
        $this->assertEquals($firstItem->getSlotsCount(), $firstItemSlots->count(), "First item is STILL slotted");

        $firstItemSlots->each(function (Slot $slot) use ($squad) {
            $this->assertEquals($squad->getLocalStash()->id, $slot->has_slots_id, "item slots belong to squad's STASH");
        });
    }

    public function provides_slotting_to_a_full_squad_will_stash_the_item_if_no_store_house_is_available()
    {
        return [
            'single replacing singe' => [
                'squadSlotsCount' => 1,
                'firstItemBase' => ItemBase::SWORD,
                'secondItemBase' => ItemBase::HELMET
            ],
            'double replacing double' => [
                'squadSlotsCount' => 2,
                'firstItemBase' => ItemBase::TWO_HAND_SWORD,
                'secondItemBase' => ItemBase::PSIONIC_TWO_HAND
            ],
            'double replacing single' => [
                'squadSlotsCount' => 2,
                'firstItemBase' => ItemBase::SHIELD,
                'secondItemBase' => ItemBase::CROSSBOW
            ]
        ];
    }
}
