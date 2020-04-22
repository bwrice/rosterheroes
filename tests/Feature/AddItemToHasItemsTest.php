<?php

namespace Tests\Feature;

use App\Domain\Actions\AddItemToHasItems;
use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\Squad;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ResidenceFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddItemToHasItemsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AddItemToHasItems
     */
    protected function getDomainAction()
    {
        return app(AddItemToHasItems::class);
    }

    /**
     * @test
     */
    public function it_will_attach_an_item_to_a_squad()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();

        $this->assertNull($item->hasItems);

        $this->getDomainAction()->execute($item, $squad);

        $hasItems = $item->fresh()->hasItems;
        $this->assertEquals($squad->id, $hasItems->getMorphID());
        $this->assertEquals(Squad::RELATION_MORPH_MAP_KEY, $hasItems->getMorphType());
    }

    /**
     * @test
     * @param $prioritize
     * @param $withResidence
     * @dataProvider provides_it_will_properly_move_squad_items_if_no_room_for_current_item
     */
    public function it_will_properly_move_squad_items_if_no_room_for_current_item($prioritize, $withResidence)
    {
        /** @var Material $material */
        $material = Material::query()->inRandomOrder()->first(); // use the same material for each
        /** @var ItemType $lightItemType */
        $lightItemType = ItemBase::forName(ItemBase::SASH)->itemTypes()->orderByDesc('id')->first();
        /** @var ItemType $heaveItemType */
        $heaveItemType = ItemBase::forName(ItemBase::SHIELD)->itemTypes()->orderByDesc('id')->first();
        $lightItem = ItemFactory::new()->withMaterial($material)->withItemType($lightItemType)->create();
        $heavyItemFactory = ItemFactory::new()->withMaterial($material)->withItemType($heaveItemType);
        $heavyItemOne = $heavyItemFactory->create();
        $heavyItemTwo = $heavyItemFactory->create();

        $squad = SquadFactory::new()->create();
        if ($withResidence) {
            $residence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        }

        $squad->items()->save($lightItem);
        $squad->items()->save($heavyItemOne);

        $lightItemWeight = $lightItem->weight();
        $heavyItemWeight = $heavyItemOne->weight();

        $this->assertGreaterThan($lightItemWeight, $heavyItemWeight);
        $this->assertEquals($heavyItemWeight, $heavyItemTwo->weight());

        $this->assertEquals($squad->mobileStorageRank->name, MobileStorageRank::WAGON);

        $mockedWagonBehavior = \Mockery::mock(WagonBehavior::class)
            ->shouldReceive('getWeightCapacity')
            ->andReturn($lightItemWeight + $heavyItemWeight)->getMock();

        app()->instance(WagonBehavior::class, $mockedWagonBehavior);

        $this->assertNull($heavyItemTwo->hasItems);

        /*
         * Run the action
         */
        $this->getDomainAction()->execute($heavyItemTwo, $squad, null, $prioritize);

        $squadItems = $squad->fresh()->items;

        if ($prioritize) {
            $heavyItemAttachedToSquad = $heavyItemTwo;
            $itemMovedToBackup = $heavyItemOne;
        } else {
            $heavyItemAttachedToSquad = $heavyItemOne;
            $itemMovedToBackup = $heavyItemTwo;
        }

        foreach ([$lightItem, $heavyItemAttachedToSquad] as $item) {
            /** @var Item $item */
            $match = $squadItems->first(function (Item $squadItem) use ($item) {
                return $squadItem->id === $item->id;
            });
            $this->assertNotNull($match);
        }

        if ($withResidence) {

            $match = $residence->items->first(function (Item $item) use ($itemMovedToBackup) {
                return $item->id === $itemMovedToBackup->id;
            });

            $this->assertNotNull($match);

        } else {

            $match = $squad->getLocalStash()->items->first(function (Item $item) use ($itemMovedToBackup) {
                return $item->id === $itemMovedToBackup->id;
            });

            $this->assertNotNull($match);
        }
    }

    public function provides_it_will_properly_move_squad_items_if_no_room_for_current_item()
    {
        return [
            'Prioritized without residence' => [
                'prioritize' => true,
                'withResidence' => false
            ],
            'Not prioritized without residence' => [
                'prioritize' => false,
                'withResidence' => false
            ],
            'Prioritized with residence' => [
                'prioritize' => true,
                'withResidence' => true
            ],
            'Not prioritized with residence' => [
                'prioritize' => false,
                'withResidence' => true
            ],
        ];
    }
}
