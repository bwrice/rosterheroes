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
use App\Domain\Actions\FillSlotAction;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FillSlotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var FillSlotAction */
    protected $domainAction;

    /** @var Hero */
    protected $hero;

    public function setUp(): void
    {
        parent::setUp();

        $this->domainAction = app(FillSlotAction::class);

        $this->hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();

        factory(HeroPost::class)->create([
            'hero_id' => $this->hero->id,
            'squad_id' => factory(Squad::class)->states('with-slots')->create()->id
        ]);
    }

    /**
     * @test
     * @dataProvider provides_it_can_slot_items_on_empty_heroes
     *
     * @param $itemBase
     * @throws \Exception
     */
    public function it_can_slot_items_on_empty_heroes($itemBase)
    {
        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $itemBase)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        $item = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        $this->domainAction->execute($this->hero, $item);

        $item = $item->fresh();

        $this->assertEquals($itemBase->getSlotsCount(), $item->slots->count(), "Item takes up the correct amount of slots");
        $item->slots->each(function (Slot $slot) use ($item) {
            $this->assertEquals($slot->slottable_id, $item->id);
            $this->assertEquals($slot->slottable_type, Item::RELATION_MORPH_MAP);
        });

        $this->assertEquals($itemBase->getSlotsCount(), $this->hero->slots->filled()->count(), "Hero has correct amount of slots filled");
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
        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $firstItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        $firstItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $secondItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        $secondItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        $this->domainAction->execute($this->hero->fresh(), $firstItem);

        $this->domainAction->execute($this->hero->fresh(), $secondItem);

        $firstItem = $firstItem->fresh();
        $secondItem = $secondItem->fresh();

        $firstItem->slots->each(function (Slot $slot) {
            $this->assertEquals($this->hero->getSquad()->id, $slot->has_slots_id, "First item now slotted in squad");
        });

        $secondItem->slots->each(function (Slot $slot) {
            $this->assertEquals($this->hero->fresh()->id, $slot->has_slots_id, "Second item now slotted in hero");
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
        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $firstItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        $firstItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $secondItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        $secondItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $thirdItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        $thirdItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        $this->domainAction->execute($this->hero->fresh(), $firstItem);
        $this->domainAction->execute($this->hero->fresh(), $secondItem);
        $this->domainAction->execute($this->hero->fresh(), $thirdItem);

        $firstItem = $firstItem->fresh();
        $secondItem = $secondItem->fresh();
        $thirdItem = $thirdItem->fresh();

        $this->assertEquals(1, $firstItem->slots->count(), "Single slot item only taking up one slot");
        $this->assertEquals(1, $secondItem->slots->count(), "Single slot item only taking up one slot");
        $this->assertEquals(1, $thirdItem->slots->count(), "Single slot item only taking up one slot");

        $slotOne = $firstItem->slots->first();
        $slotTwo = $secondItem->slots->first();
        $slotThree = $thirdItem->slots->first();

        $this->hero = $this->hero->fresh();

        $this->assertEquals($this->hero->getSquad()->id, $slotOne->has_slots_id, "First item now slotted in squad");
        $this->assertEquals($this->hero->id, $slotTwo->has_slots_id, "Second item is still slotted in hero");
        $this->assertEquals($this->hero->id, $slotThree->has_slots_id, "Third item now slotted in hero");
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
     * @param $slotsToKeepCount
     * @param $firstItemBaseName
     * @param $secondItemBaseName
     * @throws \Exception
     */
    public function slotting_to_a_full_squad_will_stash_the_item_if_no_store_house_is_available($slotsToKeepCount, $firstItemBaseName, $secondItemBaseName)
    {

        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $firstItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        /** @var Item $firstItem */
        $firstItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        /** @var \App\Domain\Models\ItemBase $itemBase */
        $itemBase = ItemBase::query()->where('name', $secondItemBaseName)->first();
        /** @var ItemType $itemType */
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();
        $materialType = $itemType->materialTypes()->inRandomOrder()->first();

        /** @var Item $secondItem */
        $secondItem = factory(Item::class)->create([
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id
        ]);

        $squad = $this->hero->getSquad();

        // delete all slots but the slots to keep
        $squadSlotCount = $squad->slots()->count();
        $squad->slots()->take($squadSlotCount - $slotsToKeepCount)->delete();

        $this->domainAction->execute($squad->fresh(), $firstItem);
        $this->domainAction->execute($squad->fresh(), $secondItem);

        $firstItem = $firstItem->fresh();
        $secondItem = $secondItem->fresh();

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
