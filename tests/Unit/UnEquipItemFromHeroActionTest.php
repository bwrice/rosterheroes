<?php

namespace Tests\Unit;

use App\Domain\Actions\UnEquipItemFromHeroAction;
use App\Domain\Behaviors\MobileStorageRank\WagonBehavior;
use App\Domain\Behaviors\StoreHouses\ShackBehavior;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\Morphable;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Residence;
use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Domain\Models\Week;
use App\Exceptions\ItemTransactionException;
use App\Facades\CurrentWeek;
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
        factory(Week::class)->states('adventuring-open', 'as-current')->create();
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
        CurrentWeek::partialMock()->shouldReceive('adventuringLocked')->andReturn(true);

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

    protected function assertItemTransactionMatches(Item $item, HasItems $to, HasItems $from)
    {
        $this->assertEquals([
            'to' => $to->getTransactionIdentification(),
            'from' => $from->getTransactionIdentification()
        ], $item->getTransaction());
    }

    /**
     * @test
     */
    public function it_will_move_an_item_to_the_squads_wagon()
    {
        $itemsMoved = $this->domainAction->execute($this->item, $this->hero);

        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $itemMoved */
        $itemMoved = $itemsMoved->first();
        $this->assertEquals($this->item->id, $itemMoved->id);
        $this->assertItemTransactionMatches($itemMoved, $this->hero->squad, $this->hero);
    }

    /**
     * @test
     */
    public function it_will_move_an_item_to_the_stash_if_wagon_full_and_no_house()
    {
        $wagonBehaviorMock = \Mockery::mock(WagonBehavior::class);
        $wagonBehaviorMock->shouldReceive('getWeightCapacity')->andReturn(-1);
        app()->instance(WagonBehavior::class, $wagonBehaviorMock);

        $itemsMoved = $this->domainAction->execute($this->item, $this->hero);
        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $itemMoved */
        $itemMoved = $itemsMoved->first();
        $this->assertEquals($this->item->id, $itemMoved->id);
        $this->assertItemTransactionMatches($itemMoved, $this->hero->squad->getLocalStash(), $this->hero);
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

        $itemsMoved = $this->domainAction->execute($this->item, $this->hero);
        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $itemMoved */
        $itemMoved = $itemsMoved->first();
        $this->assertEquals($this->item->id, $itemMoved->id);
        $this->assertItemTransactionMatches($itemMoved, $residence, $this->hero);
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
        $shackBehaviorMock->shouldReceive('getMaxItemCount')->andReturn(-1);
        app()->instance(ShackBehavior::class, $shackBehaviorMock);

        $squad = $this->hero->squad;
        $residence = factory(Residence::class)->create([
            'squad_id' => $squad->id,
            'province_id' => $squad->province_id,
        ]);

        $itemsMoved = $this->domainAction->execute($this->item, $this->hero);
        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $itemMoved */
        $itemMoved = $itemsMoved->first();
        $this->assertEquals($this->item->id, $itemMoved->id);
        $this->assertItemTransactionMatches($itemMoved, $squad->getLocalStash(), $this->hero);
    }
}
