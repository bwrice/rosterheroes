<?php

namespace Tests\Unit;

use App\Domain\Actions\GenerateItemFromBlueprintAction;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemBase;
use App\Factories\Models\AttackFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateItemFromBlueprintTest extends TestCase
{
    use DatabaseTransactions;

    /** @var GenerateItemFromBlueprintAction */
    protected $domainAction;

    /** @var ItemBlueprint */
    protected $itemBlueprint;

    public function setUp(): void
    {
        parent::setUp();

        $this->domainAction = app(GenerateItemFromBlueprintAction::class);
        $this->itemBlueprint = factory(ItemBlueprint::class)->create();
    }

    /**
     * @test
     */
    public function it_will_generate_an_item()
    {
        $item = $this->domainAction->execute($this->itemBlueprint);

        $this->assertTrue($item instanceof Item);
        $this->assertDatabaseHas( 'items', [
            'id' => $item->id
        ]);
    }

    /**
     * @test
     */
    public function it_will_attach_already_defined_enchantments()
    {
        $itemClass = ItemClass::query()->where('name', ItemClass::ENCHANTED )->first();
        $this->itemBlueprint->itemClasses()->attach($itemClass);
        $this->itemBlueprint->save();

        $enchantments = Enchantment::inRandomOrder()->take(3)->get();
        $enchantmentIDs = $enchantments->pluck('id')->toArray();

        $this->assertEquals( 3, count($enchantmentIDs));

        /** @var ItemBlueprint $blueprint */
        $this->itemBlueprint->enchantments()->attach($enchantmentIDs);

        /** @var Item $item */
        $item = $this->domainAction->execute($this->itemBlueprint->fresh());

        $this->assertDatabaseHas('items', [
            'id' => $item->id
        ]);

        $item = $item->fresh();
        $actualEnchantments = $item->enchantments()->get();

        $this->assertEquals($enchantmentIDs, array_intersect($enchantmentIDs, $actualEnchantments->pluck('id')->toArray()));
    }

    /**
     * @test
     */
    public function it_will_attach_enchantments_when_generating_an_enchanted_class_item_when_not_defined()
    {
        $itemClass = ItemClass::query()->where('name', ItemClass::ENCHANTED )->first();
        $this->itemBlueprint->itemClasses()->attach($itemClass);
        $this->itemBlueprint->save();

        $this->assertEquals(0, $this->itemBlueprint->enchantments->count());
        $item = $this->domainAction->execute($this->itemBlueprint->fresh());

        $this->assertGreaterThan(0, $item->enchantments->count());
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
        $itemBaseForBlueprint = ItemBase::query()->where('name', '=', $itemBaseName)->first();

        $this->itemBlueprint->itemBases()->attach($itemBaseForBlueprint);

        $item = $this->domainAction->execute($this->itemBlueprint->fresh());

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
            ItemBase::POLEARM => [
                'item_base' => ItemBase::POLEARM,
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
//            ItemBase::EYE_WEAR => [
//                'item_base' => ItemBase::EYE_WEAR,
//            ],
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
     */
    public function it_will_create_an_item_with_attacks_that_matches_the_blueprints_attacks()
    {

        $this->itemBlueprint->itemTypes()->sync([]);
        $swordBase = ItemBase::query()->where('name', '=', ItemBase::SWORD)->first();
        $this->itemBlueprint->itemBases()->save($swordBase);
        $this->itemBlueprint->save();

        $attackFactory = AttackFactory::new();
        $attacks = collect();
        foreach (range(1, rand(1,5)) as $count) {
            $attacks->push($attackFactory->create());
        }
        $this->itemBlueprint->attacks()->saveMany($attacks);
        $this->assertGreaterThan(0, $attacks->count());

        $item = $this->domainAction->execute($this->itemBlueprint->fresh());
        $this->assertEquals($attacks->count(), $item->attacks->count());
        $this->assertEquals($attacks->pluck('id')->values()->toArray(), $item->attacks->pluck('id')->values()->toArray());
    }

}
