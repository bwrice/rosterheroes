<?php

namespace Tests\Unit;

use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\Item;
use App\ItemBlueprint;
use App\Items\ItemBases\ItemBase;
use App\ItemType;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slotter;
use App\Squad;
use App\User;
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

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->squadName = 'TestingSquad_' . uniqid();
        $this->squad = Squad::generate($this->user->id, $this->squadName, [
            [
                'name' => 'TestHero1',
                'race' => HeroRace::HUMAN,
                'class' => HeroClass::RANGER
            ],
            [
                'name' => 'TestHero2',
                'race' => HeroRace::ELF,
                'class' => HeroClass::SORCERER
            ],
            [
                'name' => 'TestHero3',
                'race' => HeroRace::ORC,
                'class' => HeroClass::WARRIOR
            ],
            [
                'name' => 'TestHero4',
                'race' => HeroRace::DWARF,
                'class' => HeroClass::WARRIOR
            ]
        ]);

        $this->slotter = app()->make(Slotter::class);
    }

    /**
     * @test
     * @dataProvider provides_it_can_slot_items_on_empty_heroes
     *
     * @param $itemBase
     */
    public function it_can_slot_items_on_empty_heroes($itemBase)
    {
        /** @var Hero $hero */
        $hero = $this->squad->heroes->random();

        /** @var ItemBase $itemBase */
        $itemBase = ItemBase::where('name', $itemBase)->first();

        /** @var ItemBlueprint $blueprint */
        $blueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $item = $blueprint->generate();

        $this->slotter->slot($hero, $item);

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
     * @dataProvider provides_it_can_slot_and_replace_items_on_heroes_and_move_them_to_wagon
     *
     * @param $firstItemBaseName
     * @param $secondItemBaseName
     */
    public function it_can_slot_and_replace_items_on_heroes_and_move_them_to_wagon($firstItemBaseName, $secondItemBaseName)
    {
        /** @var Hero $hero */
        $hero = $this->squad->heroes->random();

        /** @var ItemBase $firstItemBase */
        $firstItemBase = ItemBase::where('name', $firstItemBaseName)->first();

        /** @var ItemBlueprint $firstBlueprint */
        $firstBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $firstItemBase->id
        ]);

        $itemOne = $firstBlueprint->generate();

        $this->slotter->slot($hero, $itemOne);

        /** @var ItemBase $firstItemBase */
        $secondItemBase = ItemBase::where('name', $secondItemBaseName)->first();

        $secondBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $secondItemBase->id
        ]);


        $itemTwo = $secondBlueprint->generate();

        $this->slotter->slot($hero, $itemTwo);

        $wagon = $this->squad->wagon;

        $itemOne->slots->each(function (Slot $slot) use ($wagon) {
            $this->assertEquals($wagon->id, $slot->has_slots_id, "First item now slotted in wagon");
        });

        $itemTwo->slots->each(function (Slot $slot) use ($hero) {
            $this->assertEquals($hero->id, $slot->has_slots_id, "Second item now slotted in hero");
        });
    }

    public function provides_it_can_slot_and_replace_items_on_heroes_and_move_them_to_wagon()
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
     * @dataProvider provides_it_can_slot_single_slot_items_with_multi_slot_options_and_only_move_one_to_the_wagon_if_possible
     */
    public function it_can_slot_single_slot_items_with_multi_slot_options_and_only_move_one_to_the_wagon_if_possible($firstItemBaseName, $secondItemBaseName, $thirdItemBaseName)
    {
        /** @var Hero $hero */
        $hero = $this->squad->heroes->random();

        /** @var ItemBase $itemBase */
        $itemBase = ItemBase::where('name', $firstItemBaseName)->first();

        /** @var ItemBlueprint $itemBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $itemOne = $itemBlueprint->generate();

        $this->slotter->slot($hero, $itemOne);
        /** @var ItemBase $firstItemBase */
        $itemBase = ItemBase::where('name', $secondItemBaseName)->first();

        /** @var ItemBlueprint $firstBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $itemTwo = $itemBlueprint->generate();

        $this->slotter->slot($hero, $itemTwo);

        /** @var ItemBase $firstItemBase */
        $itemBase = ItemBase::where('name', $thirdItemBaseName)->first();

        /** @var ItemBlueprint $firstBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $itemThree = $itemBlueprint->generate();

        $this->slotter->slot($hero, $itemThree);

        $wagon = $this->squad->wagon;

        $this->assertEquals(1, $itemOne->slots->count(), "Single slot item only taking up one slot");
        $this->assertEquals(1, $itemTwo->slots->count(), "Single slot item only taking up one slot");
        $this->assertEquals(1, $itemThree->slots->count(), "Single slot item only taking up one slot");

        $slotOne = $itemOne->slots->first();
        $slotTwo = $itemTwo->slots->first();
        $slotThree = $itemThree->slots->first();


        $this->assertEquals($wagon->id, $slotOne->has_slots_id, "First item now slotted in wagon");
        $this->assertEquals($hero->id, $slotTwo->has_slots_id, "Second item is still slotted in hero");
        $this->assertEquals($hero->id, $slotThree->has_slots_id, "Third item now slotted in hero");
    }

    public function provides_it_can_slot_single_slot_items_with_multi_slot_options_and_only_move_one_to_the_wagon_if_possible()
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
     */
    public function slotting_to_a_full_wagon_will_stash_the_item_if_no_store_house_is_available()
    {
        $firstItemBaseName = ItemBase::SWORD;
        $itemBase = ItemBase::where('name', '=', $firstItemBaseName)->first();

        /** @var ItemBlueprint $itemBlueprint */
        $itemBlueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBase->id
        ]);

        $firstItem = $itemBlueprint->generate();

        $wagonSlotCount = $this->squad->wagon->slots()->count();
        // delete all but the slots needed for the first item
        $wagon = $this->squad->wagon;
        $wagon->slots()->take($wagonSlotCount - $firstItem->getSlotsCount())->delete();
        $wagon = $wagon->fresh();

        $this->slotter->slot($wagon, $firstItem);
        //TODO build test for Squad that it will create a stash or return a storehouse
    }
}
