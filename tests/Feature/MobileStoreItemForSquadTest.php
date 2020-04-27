<?php

namespace Tests\Feature;

use App\Domain\Actions\MobileStoreItemForSquad;
use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\Province;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ResidenceFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestsItemsTransactions;

class MobileStoreItemForSquadTest extends TestCase
{
    use DatabaseTransactions;
    use TestsItemsTransactions;

    /**
     * @return MobileStoreItemForSquad
     */
    protected function getDomainAction()
    {
        return app(MobileStoreItemForSquad::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_belongs_to_a_diff_squad()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $diffSquad = SquadFactory::new()->create();
        $diffSquad->items()->save($item);

        try {
            $this->getDomainAction()->execute($item, $squad);
        } catch (\Exception $exception) {
            $this->assertTrue($item->ownedByMorphable($diffSquad));
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_item_is_stashed_at_a_different_province()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        /** @var Province $diffProvince */
        $diffProvince = Province::query()->where('id', '!=', $squad->province_id)->inRandomOrder()->first();
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($diffProvince)->create();
        $stash->items()->save($item);

        try {
            $this->getDomainAction()->execute($item, $squad);
        } catch (\Exception $exception) {
            $this->assertTrue($item->ownedByMorphable($stash));
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_item_at_a_residence_in_a_different_province()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        /** @var Province $diffProvince */
        $diffProvince = Province::query()->where('id', '!=', $squad->province_id)->inRandomOrder()->first();
        $residence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($diffProvince)->create();
        $residence->items()->save($item);

        try {
            $this->getDomainAction()->execute($item, $squad);
        } catch (\Exception $exception) {
            $this->assertTrue($item->ownedByMorphable($residence));
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_add_the_item_to_squad_mobile_storage_from_a_local_stash()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $stash->items()->save($item);

        $itemsMoved = $this->getDomainAction()->execute($item, $squad);

        $this->assertEquals(1, $itemsMoved->count());
        /** @var Item $itemMoved */
        $itemMoved = $itemsMoved->first();
        $this->assertEquals($item->id, $itemMoved->id);
        $this->assertItemTransactionMatches($itemMoved, $squad, $stash);
    }

    /**
     * @test
     */
    public function it_will_add_the_item_to_squad_mobile_storage_from_a_local_residence()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $residence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $residence->items()->save($item);

        $itemsMoved = $this->getDomainAction()->execute($item, $squad);

        $this->assertEquals(1, $itemsMoved->count());
        /** @var Item $itemMoved */
        $itemMoved = $itemsMoved->first();
        $this->assertEquals($item->id, $itemMoved->id);
        $this->assertItemTransactionMatches($itemMoved, $squad, $residence);
    }

    /**
     * @test
     */
    public function it_will_swap_items_from_stash_to_make_room_for_new_item()
    {
        /*
         * Need a heavy item since we are mocking the wagon capacity based on it's weight
         */
        $itemAlreadyInWagon = ItemFactory::new()->fromItemBases([ItemBase::SHIELD])->create();
        $squad = SquadFactory::new()->create();
        $squad->items()->save($itemAlreadyInWagon);

        /*
         * Need a light item because the wagon capacity is mocked based on the item already in the wagon
         */
        $itemToStore = ItemFactory::new()->fromItemBases([ItemBase::RING])->create();
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $stash->items()->save($itemToStore);

        $mock = \Mockery::mock(WagonBehavior::class)
            ->shouldReceive('getWeightCapacity')
            ->andReturn($itemAlreadyInWagon->weight())
            ->getMock();

        app()->instance(WagonBehavior::class, $mock);

        $itemsMoved = $this->getDomainAction()->execute($itemToStore, $squad);

        $this->assertEquals(2, $itemsMoved->count());

        /** @var Item $itemMovedToWagon */
        $itemMovedToWagon = $itemsMoved->first(function (Item $item) use ($itemToStore) {
            return $item->id === $itemToStore->id;
        });
        $this->assertNotNull($itemMovedToWagon);
        $this->assertItemTransactionMatches($itemMovedToWagon, $squad, $stash);

        /** @var Item $itemMovedToWagon */
        $itemMovedToStash = $itemsMoved->first(function (Item $item) use ($itemAlreadyInWagon) {
            return $item->id === $itemAlreadyInWagon->id;
        });
        $this->assertNotNull($itemMovedToStash);
        $this->assertItemTransactionMatches($itemMovedToStash, $stash, $squad);
    }
}
