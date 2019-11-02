<?php

namespace Tests\Unit;

use App\Domain\Actions\UnEquipItemFromHeroAction;
use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Behaviors\StoreHouses\ShackBehavior;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Residence;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\Week;
use App\Exceptions\ItemTransactionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnEquipItemFromHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Item */
    protected $item;

    /** @var UnEquipItemFromHeroAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->states('with-measurables')->create();
        $this->item = factory(Item::class)->create([
            'has_items_type' => Hero::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->hero->id
        ]);
        /** @var Week $week */
        $week = factory(Week::class)->states('adventuring-open', 'as-current')->create();
        $week->everything_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);
        $this->domainAction = app(UnEquipItemFromHeroAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_anyone()
    {
        $this->item = $this->item->clearHasItems();

        try {
            $this->domainAction->execute($this->item->fresh(), $this->hero);

        } catch (ItemTransactionException $exception) {
            $this->assertEquals(ItemTransactionException::CODE_INVALID_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_week_is_locked_for_adventuring()
    {
        factory(Week::class)->states('adventuring-closed', 'as-current')->create();

        try {
            $this->domainAction->execute($this->item, $this->hero);

        } catch (ItemTransactionException $exception) {
            $this->assertEquals(ItemTransactionException::CODE_TRANSACTION_DISABLED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_the_hero()
    {
        $hero = factory(Hero::class)->create();
        $this->item = $this->item->attachToHasItems($hero);

        try {
            $this->domainAction->execute($this->item->fresh(), $this->hero);

        } catch (ItemTransactionException $exception) {
            $this->assertEquals(ItemTransactionException::CODE_INVALID_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_move_an_item_to_the_squads_wagon()
    {
        $hasItemsCollection = $this->domainAction->execute($this->item, $this->hero);

        $this->item = $this->item->fresh();
        $this->assertEquals(Squad::RELATION_MORPH_MAP_KEY, $this->item->has_items_type);

        $squad = $this->hero->squad;
        $this->assertEquals($squad->id, $this->item->has_items_id);

        $this->assertEquals(2, $hasItemsCollection->count());

        $hero = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->hero->id && $hasItems->getMorphType() === Hero::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($hero);

        $squadHasItems = $hasItemsCollection->first(function (HasItems $hasItems) use ($squad) {
            return $hasItems->getMorphID() === $squad->id && $hasItems->getMorphType() === Squad::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($squadHasItems);
    }

    /**
     * @test
     */
    public function it_will_move_an_item_to_the_stash_if_wagon_full_and_no_house()
    {
        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class);
        $wagonBehaviorMock->shouldReceive('getWeightCapacity')->andReturn(-1);
        app()->instance(WagonBehavior::class, $wagonBehaviorMock);

        $hasItemsCollection = $this->domainAction->execute($this->item, $this->hero);
        $this->assertEquals(2, $hasItemsCollection->count());

        $hero = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->hero->id && $hasItems->getMorphType() === Hero::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($hero);

        $stashHasItems = $hasItemsCollection->first(function (HasItems $hasItems) {
            $stash = $this->hero->squad->getLocalStash();
            return $hasItems->getMorphID() === $stash->id && $hasItems->getMorphType() === Stash::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($stashHasItems);
    }

    /**
     * @test
     */
    public function it_will_move_an_item_to_a_residence_if_available_and_mobile_storage_is_full()
    {
        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class);
        $wagonBehaviorMock->shouldReceive('getWeightCapacity')->andReturn(-1);
        app()->instance(WagonBehavior::class, $wagonBehaviorMock);

        $squad = $this->hero->squad;
        $residence = factory(Residence::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id,
        ]);

        $hasItemsCollection = $this->domainAction->execute($this->item, $this->hero);
        $this->assertEquals(2, $hasItemsCollection->count());

        $hero = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->hero->id && $hasItems->getMorphType() === Hero::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($hero);

        $residenceHasItems = $hasItemsCollection->first(function (HasItems $hasItems) use ($residence) {
            return $hasItems->getMorphID() === $residence->id && $hasItems->getMorphType() === Residence::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($residenceHasItems);
    }

    /**
     * @test
     */
    public function it_will_move_an_item_to_stash_if_mobile_storage_is_full_and_available_residence_is_full()
    {
        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class);
        $wagonBehaviorMock->shouldReceive('getWeightCapacity')->andReturn(-1);
        app()->instance(WagonBehavior::class, $wagonBehaviorMock);

        $shackBehaviorMock = \Mockery::mock(ShackBehavior::class);
        $shackBehaviorMock->shouldReceive('getMaxItemCount')->andReturn(0);
        app()->instance(ShackBehavior::class, $shackBehaviorMock);

        $squad = $this->hero->squad;
        $residence = factory(Residence::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id,
        ]);

        $hasItemsCollection = $this->domainAction->execute($this->item, $this->hero);
        $this->assertEquals(2, $hasItemsCollection->count());

        $hero = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->hero->id && $hasItems->getMorphType() === Hero::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($hero);

        $stashHasItems = $hasItemsCollection->first(function (HasItems $hasItems) use ($squad) {
            $stash = $squad->getLocalStash();
            return $hasItems->getMorphID() === $stash->id && $hasItems->getMorphType() === Stash::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($stashHasItems);
    }
}
