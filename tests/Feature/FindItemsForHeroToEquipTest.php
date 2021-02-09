<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindItemsForHeroToEquip;
use App\Domain\Models\HeroClass;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindItemsForHeroToEquipTest extends TestCase
{
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
     * @dataProvider provides_it_will_find_weapons_based_on_hero_class
     */
    public function it_will_find_weapons_for_primary_arm_based_on_hero_class(string $heroClassname, string $expectedItemBaseName, array $unexpectedItemBaseNames)
    {
        $hero = HeroFactory::new()->withMeasurables()->heroClass($heroClassname)->create();
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
    public function provides_it_will_find_weapons_based_on_hero_class()
    {
        return [
            HeroClass::WARRIOR => [
                'heroClassName' => HeroClass::WARRIOR,
                'expectedItemBaseName' => ItemBase::SWORD,
                'unexpectedItemBaseNames' => [
                    ItemBase::BOW,
                    ItemBase::STAFF
                ]
            ],
            HeroClass::RANGER => [
                'heroClassName' => HeroClass::RANGER,
                'expectedItemBaseName' => ItemBase::BOW,
                'unexpectedItemBaseNames' => [
                    ItemBase::SWORD,
                    ItemBase::STAFF
                ]
            ],
            HeroClass::SORCERER => [
                'heroClassName' => HeroClass::SORCERER,
                'expectedItemBaseName' => ItemBase::STAFF,
                'unexpectedItemBaseNames' => [
                    ItemBase::SWORD,
                    ItemBase::BOW
                ]
            ],
        ];
    }
}
