<?php

namespace Tests\Unit;

use App\Enchantment;
use App\Item;
use App\ItemBlueprint;
use App\ItemClass;
use App\ItemGroup;
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
        $blueprint = factory( ItemBlueprint::class )->create();

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
     */
    public function it_can_create_a_weapon_with_attacks()
    {
        $this->assertTrue(false, "TODO attacks for item blueprint generation");
        //TODO
        $itemType = ItemType::where('name', 'short sword')->first();

        $this->assertNotNull( $itemType );

        /** @var Collection $attacks */
        $attacks = Attack::inRandomOrder()->take(2)->get();

        $this->assertEquals( 2, $attacks->count() );

        $attackIDs = $attacks->pluck('id')->toArray();

        $blueprint = factory( ItemBlueprint::class )->create([
            'item_type_id' => $itemType,
        ]);

        /** @var ItemBlueprint $blueprint */
        $blueprint->attacks()->attach( $attackIDs );

        /** @var Item $item */
        $item = $blueprint->generate();

        $this->assertDatabaseHas( 'items', [
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

        $blueprint = factory( ItemBlueprint::class )->create([
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
