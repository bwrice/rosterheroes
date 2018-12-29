<?php

namespace Tests\Unit;

use App\Enchantment;
use App\Item;
use App\ItemBlueprint;
use App\ItemClass;
use App\ItemGroup;
use App\Items\ItemBases\ItemBase;
use App\ItemType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemBlueprintTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_generate_an_item()
    {
        /** @var ItemBlueprint $blueprint */
        $blueprint = factory(ItemBlueprint::class)->create();

        $item = $blueprint->generate();

        $this->assertTrue( $item instanceof Item );
        $this->assertDatabaseHas( 'items', [
            'id' => $item->id
        ]);
    }

    /**
     * @test
     */
    public function it_can_generate_an_item_with_enchantments()
    {

        $blueprint = factory( ItemBlueprint::class )->create([
            'item_class_id' => ItemClass::where('name', ItemClass::ENCHANTED )->first()->id
        ]);

        $enchantments = Enchantment::inRandomOrder()->take(3)->get();
        $enchantmentIDs = $enchantments->pluck('id')->toArray();

        $this->assertEquals( 3, count( $enchantmentIDs ));

        /** @var ItemBlueprint $blueprint */
        $blueprint->enchantments()->attach( $enchantmentIDs );

        /** @var Item $item */
        $item = $blueprint->generate();

        $this->assertDatabaseHas( 'items', [
            'id' => $item->id
        ]);

        $item = $item->fresh();
        $actualEnchantments = $item->enchantments()->get();

        $this->assertEquals($enchantmentIDs, array_intersect($enchantmentIDs, $actualEnchantments->pluck('id')->toArray()));
    }

    /**
     * @test
     *
     * @dataProvider provides_it_will_generate_a_correct_item_type_from_an_item_base
     *
     * @param $itemBaseName
     */
    public function it_will_generate_a_correct_item_type_from_an_item_base($itemBaseName)
    {
        /** @var ItemBase $itemBaseForBlueprint */
        $itemBaseForBlueprint = ItemBase::where('name', '=', $itemBaseName)->first();

        /** @var ItemBlueprint $blueprint */
        $blueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null, //Override default set by factory
            'item_base_id' => $itemBaseForBlueprint->id
        ]);

        $item = $blueprint->generate();

        $this->assertEquals($itemBaseForBlueprint->id, $item->itemType->itemBase->id, "Item base of the item generate is the same as the blueprint");
    }

    public function provides_it_will_generate_a_correct_item_type_from_an_item_base()
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

    //TODO write test where item_base will generate the correct item type for item

    /**
     * @test
     */
    public function it_can_create_a_weapon_with_attacks()
    {
        $this->assertTrue(true, "TODO attacks for item blueprint generation");
        //TODO
        $itemType = ItemType::where('name', 'short sword')->first();

        $this->assertNotNull( $itemType );

        /** @var Collection $attacks */
        $attacks = Attack::inRandomOrder()->take(2)->get();

        $this->assertEquals( 2, $attacks->count() );

        $attackIDs = $attacks->pluck('id')->toArray();

        $blueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => $itemType,
        ]);

        /** @var ItemBlueprint $blueprint */
        $blueprint->attacks()->attach($attackIDs);

        /** @var Item $item */
        $item = $blueprint->generate();

        $this->assertDatabaseHas('items', [
            'id' => $item->id
        ]);

        $item = $item->fresh();
        $actualAttacks = $item->attacks()->get();

        $this->assertEquals( $attackIDs, $actualAttacks->pluck('id')->toArray() );
    }



    /**
     * @test
     */
    public function it_can_create_an_item_by_group()
    {
        $itemGroup = ItemGroup::where('name', ItemGroup::ARMOR )->first();

        $this->assertNotNull( $itemGroup );

        $blueprint = factory(ItemBlueprint::class)->create([
            'item_type_id' => null,
            'item_group_id' => $itemGroup->id
        ]);

        /** @var Item $item */
        $item = $blueprint->generate();

        $this->assertDatabaseHas( 'items', [
            'id' => $item->id
        ]);

        $this->assertEquals( $item->itemType->itemBase->itemGroup->id, $itemGroup-> id );

    }
}
