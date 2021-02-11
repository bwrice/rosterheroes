<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindItemsForHeroToEquip;
use App\Domain\Models\HeroClass;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Support\GearSlots\GearSlot;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindItemsForHeroToEquipTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindItemsForHeroToEquip
     */
    protected function getDomainAction()
    {
        return app(FindItemsForHeroToEquip::class);
    }

    /**
     * @test
     * @param $heroClassname
     * @param $expectedItemBaseName
     * @param $unexpectedItemBaseNames
     * @dataProvider provides_it_will_find_weapons_for_primary_arm_based_on_hero_class
     */
    public function it_will_find_weapons_for_primary_arm_based_on_hero_class(string $heroClassName, string $expectedItemBaseName, array $unexpectedItemBaseNames)
    {
        $hero = HeroFactory::new()->withMeasurables()->heroClass($heroClassName)->create();
        /** @var ItemBase $expectedItemBase */
        $expectedItemBase = ItemBase::query()->where('name', '=', $expectedItemBaseName)->first();
        /** @var ItemType $expectedItemType */
        $expectedItemType = $expectedItemBase->itemTypes()->orderBy('tier')->first();
        ItemFactory::new()->withItemType($expectedItemType)->forSquad($hero->squad)->create();

        foreach ($unexpectedItemBaseNames as $baseName) {
            /** @var ItemBase $unexpectedItemBase */
            $unexpectedItemBase = ItemBase::query()->where('name', '=', $baseName)->first();
            $itemType = $unexpectedItemBase->itemTypes()->orderBy('tier')->first();
            ItemFactory::new()->withItemType($itemType)->forSquad($hero->squad)->create();
        }

        $this->assertEquals(count($unexpectedItemBaseNames) + 1, $hero->squad->fresh()->items()->count());

        $itemsToEquip = $this->getDomainAction()->execute($hero);
        $this->assertEquals(1, $itemsToEquip->count());
        /** @var Item $item */
        $item = $itemsToEquip->first();
        $this->assertEquals($expectedItemType->id, $item->item_type_id);
    }

    /**
     *
     */
    public function provides_it_will_find_weapons_for_primary_arm_based_on_hero_class()
    {
        return [
            HeroClass::WARRIOR => [
                'heroClassName' => HeroClass::WARRIOR,
                'expectedItemBaseName' => ItemBase::SWORD,
                'unexpectedItemBaseNames' => [
                    ItemBase::BOW,
                    ItemBase::STAFF
                ],
                'shield' => true
            ],
            HeroClass::RANGER => [
                'heroClassName' => HeroClass::RANGER,
                'expectedItemBaseName' => ItemBase::BOW,
                'unexpectedItemBaseNames' => [
                    ItemBase::SWORD,
                    ItemBase::STAFF
                ],
                'shield' => false
            ],
            HeroClass::SORCERER => [
                'heroClassName' => HeroClass::SORCERER,
                'expectedItemBaseName' => ItemBase::STAFF,
                'unexpectedItemBaseNames' => [
                    ItemBase::SWORD,
                    ItemBase::BOW
                ],
                'shield' => false
            ],
        ];
    }

    /**
     * @test
     * @param $heroClassName
     * @param $shield
     * @dataProvider provides_it_will_find_a_shield_based_on_hero_class
     */
    public function it_will_find_a_shield_based_on_hero_class(string $heroClassName, bool $shield)
    {
        $hero = HeroFactory::new()->withMeasurables()->heroClass($heroClassName)->create();
        /** @var ItemBase $shieldBase */
        $shieldBase = ItemBase::query()->where('name', '=', ItemBase::SHIELD)->first();
        /** @var ItemType $shieldType */
        $shieldType = $shieldBase->itemTypes()->orderBy('tier')->first();
        $shieldItem = ItemFactory::new()->withItemType($shieldType)->forSquad($hero->squad)->create();

        $itemsToEquip = $this->getDomainAction()->execute($hero);
        if ($shield) {
            $this->assertEquals(1, $itemsToEquip->count());
            $itemToEquip = $itemsToEquip->first();
            $this->assertEquals($shieldItem->id, $itemToEquip->id);
        } else {
            $this->assertEquals(0, $itemsToEquip->count());
        }
    }

    /**
     *
     */
    public function provides_it_will_find_a_shield_based_on_hero_class()
    {
        return [
            HeroClass::WARRIOR => [
                'heroClassName' => HeroClass::WARRIOR,
                'shield' => true
            ],
            HeroClass::RANGER => [
                'heroClassName' => HeroClass::RANGER,
                'shield' => false
            ],
            HeroClass::SORCERER => [
                'heroClassName' => HeroClass::SORCERER,
                'shield' => false
            ],
        ];
    }

    /**
     * @test
     */
    public function it_will_find_items_for_non_arm_gear_slots()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $itemFactory = ItemFactory::new()->forSquad($hero->squad);
        $itemBases = ItemBase::all();
        $hero->getGearSlots()->filter(function (GearSlot $gearSlot) {
            return in_array($gearSlot->getType(), [
                GearSlot::FEET,
                GearSlot::HANDS,
                GearSlot::HEAD,
                GearSlot::WAIST,
                GearSlot::TORSO
            ]);
        })->each(function (GearSlot $gearSlot) use ($itemFactory, $itemBases) {
            /** @var ItemBase $base */
            $base = $itemBases->filter(function (ItemBase $itemBase) use ($gearSlot) {
                return in_array($gearSlot->getType(), $itemBase->getSlotTypeNames());
            })->shuffle()->first();
            /** @var ItemType $itemType */
            $itemType = $base->itemTypes()->orderBy('tier')->first();
            $itemFactory->withItemType($itemType)->create();
        });

        $wagonItems = $hero->squad->items;
        $this->assertGreaterThanOrEqual(1, $wagonItems->count());

        $itemsToEquip = $this->getDomainAction()->execute($hero);

        $this->assertArrayElementsEqual($itemsToEquip->values()->pluck('id')->toArray(), $wagonItems->values()->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_will_NOT_find_duplicate_items_for_wrist_slots()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        /** @var ItemBase $expectedItemBase */
        $expectedItemBase = ItemBase::query()->where('name', '=', ItemBase::BRACELET)->first();
        /** @var ItemType $expectedItemType */
        $expectedItemType = $expectedItemBase->itemTypes()->orderBy('tier')->first();
        ItemFactory::new()->withItemType($expectedItemType)->forSquad($hero->squad)->create();

        $itemsToEquip = $this->getDomainAction()->execute($hero);

        $this->assertEquals(1, $itemsToEquip->count());
    }

    /**
     * @test
     */
    public function it_will_find_an_item_with_better_enchantments()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->where('name', '=', ItemBase::LIGHT_ARMOR);
        })->orderBy('tier')->first();

        $factory = ItemFactory::new()->forSquad($hero->squad)->withItemType($itemType);
        $nonEnchantedItem = $factory->create();
        $enchantedItem = $factory->withEnchantments()->create();

        for ($i = 1; $i <= 5; $i++) {
            // Create a few more non-enchanted items to prevent false positive
            $factory->create();
        }

        $itemsToEquip = $this->getDomainAction()->execute($hero);

        $this->assertEquals(1, $itemsToEquip->count());
        $this->assertEquals($enchantedItem->id, $itemsToEquip->first()->id);
    }

    /**
     * @test
     */
    public function it_will_find_a_better_item_to_replace_an_equipped_item()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->where('name', '=', ItemBase::LIGHT_ARMOR);
        })->orderBy('tier')->first();

        $factory = ItemFactory::new()->withItemType($itemType);
        $equippedItem = $factory->forHero($hero)->create();
        $betterItem = $factory->forSquad($hero->squad)->withEnchantments()->create();

        $itemsToEquip = $this->getDomainAction()->execute($hero);

        $this->assertEquals(1, $itemsToEquip->count());
        $this->assertEquals($betterItem->id, $itemsToEquip->first()->id);
    }

    /**
     * @test
     */
    public function it_will_NOT_find_an_item_to_place_a_better_equipped_item()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->where('name', '=', ItemBase::LIGHT_ARMOR);
        })->orderBy('tier')->first();

        $factory = ItemFactory::new()->withItemType($itemType);
        $equippedItem = $factory->forHero($hero)->withEnchantments()->create();
        $betterItem = $factory->forSquad($hero->squad)->create();

        $itemsToEquip = $this->getDomainAction()->execute($hero);

        $this->assertEquals(0, $itemsToEquip->count());
    }
}
