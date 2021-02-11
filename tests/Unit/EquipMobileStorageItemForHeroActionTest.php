<?php

namespace Tests\Unit;

use App\Domain\Actions\EquipItemForHeroAction;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\Morphable;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\ItemTransactionException;
use App\Facades\CurrentWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestsItemsTransactions;

class EquipMobileStorageItemForHeroActionTest extends TestCase
{

    use TestsItemsTransactions;
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var Item */
    protected $randomItem;
    /** @var Item */
    protected $singleHandedItem;
    /** @var Item */
    protected $twoHandedItem;
    /** @var Item */
    protected $shield;
    /** @var Item */
    protected $headItem;

    /** @var EquipItemForHeroAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->states('with-measurables')->create();
        $this->squad = $this->hero->squad;
        $this->randomItem = factory(Item::class)->create([
            'has_items_type' => Squad::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->squad->id
        ]);
        $this->singleHandedItem = factory(Item::class)->state('single-handed')->create([
            'has_items_type' => Squad::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->squad->id
        ]);
        $this->twoHandedItem = factory(Item::class)->state('two-handed')->create([
            'has_items_type' => Squad::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->squad->id
        ]);
        $this->shield = factory(Item::class)->state('shield')->create([
            'has_items_type' => Squad::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->squad->id
        ]);
        $this->headItem = factory(Item::class)->state('head')->create([
            'has_items_type' => Squad::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->squad->id
        ]);

        /** @var Week $week */
        $week = factory(Week::class)->states('adventuring-open', 'as-current')->create();
        $this->domainAction = app(EquipItemForHeroAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_anyone()
    {
        $this->randomItem = $this->randomItem->clearHasItems();

        try {
            $this->domainAction->execute($this->randomItem->fresh(), $this->hero);

        } catch (ItemTransactionException $exception) {
            $this->assertEquals(ItemTransactionException::CODE_INVALID_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_the_wagon_of_the_hero()
    {
        $squad = factory(Squad::class)->create();
        $this->randomItem = $this->randomItem->attachToHasItems($squad);

        try {
            $this->domainAction->execute($this->randomItem->fresh(), $this->hero);

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
            $this->domainAction->execute($this->randomItem, $this->hero);

        } catch (ItemTransactionException $exception) {
            $this->assertEquals(ItemTransactionException::CODE_TRANSACTION_DISABLED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_equip_an_item_on_an_empty_hero()
    {
        $itemsMoved = $this->domainAction->execute($this->randomItem, $this->hero);
        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $item */
        $item = $itemsMoved->first();
        $this->assertTrue($item->ownedByMorphable($this->hero));
        $this->assertItemTransactionMatches($item, $this->hero, $this->squad);
    }

    /**
     * @test
     */
    public function it_will_replace_a_head_item_with_another_head_item()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('head')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        $itemsMoved = $this->domainAction->execute($this->headItem, $this->hero);
        $this->assertEquals(2, $itemsMoved->count());

        $itemMovedToHero = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->hero);
        });
        $this->assertNotNull($itemMovedToHero);
        $this->assertEquals($this->headItem->id, $itemMovedToHero->id);
        $this->assertItemTransactionMatches($itemMovedToHero, $this->hero, $this->squad);

        $itemMovedToSquad = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->squad);
        });
        $this->assertNotNull($itemMovedToSquad);
        $this->assertEquals($itemPreviouslyOnHero->id, $itemMovedToSquad->id);
        $this->assertItemTransactionMatches($itemMovedToSquad, $this->squad, $this->hero);
    }

    /**
     * @test
     */
    public function it_will_not_replace_original_single_hand_item_when_equipping_another_one()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        $itemsMoved = $this->domainAction->execute($this->singleHandedItem, $this->hero);
        $this->assertTrue($itemPreviouslyOnHero->fresh()->ownedByMorphable($this->hero));
        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $item */
        $item = $itemsMoved->first();
        $this->assertTrue($item->ownedByMorphable($this->hero));
        $this->assertItemTransactionMatches($item, $this->hero, $this->squad);
    }

    /**
     * @test
     */
    public function it_will_not_replace_single_hand_item_when_equipping_a_shield()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        $itemsMoved = $this->domainAction->execute($this->shield, $this->hero);

        $this->assertTrue($itemPreviouslyOnHero->fresh()->ownedByMorphable($this->hero));
        $this->assertEquals(1, $itemsMoved->count());

        /** @var Item $item */
        $item = $itemsMoved->first();
        $this->assertTrue($item->ownedByMorphable($this->hero));
        $this->assertItemTransactionMatches($item, $this->hero, $this->squad);
    }

    /**
     * @test
     */
    public function it_will_replace_single_hand_item_with_two_hand_item()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        $itemsMoved = $this->domainAction->execute($this->twoHandedItem, $this->hero);
        $this->assertEquals(2, $itemsMoved->count());

        $itemMovedToHero = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->hero);
        });
        $this->assertNotNull($itemMovedToHero);
        $this->assertItemTransactionMatches($itemMovedToHero, $this->hero, $this->squad);

        $itemMovedToSquad = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->squad);
        });
        $this->assertNotNull($itemMovedToSquad);
        $this->assertEquals($itemPreviouslyOnHero->id, $itemMovedToSquad->id);
        $this->assertItemTransactionMatches($itemMovedToSquad, $this->squad, $this->hero);
    }

    /**
     * @test
     */
    public function it_will_replace_both_single_hand_items_with_two_hand_item()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        /** @var Item $itemPreviouslyOnHeroTwo */
        $itemPreviouslyOnHeroTwo = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHeroTwo);

        $itemsMoved = $this->domainAction->execute($this->twoHandedItem, $this->hero);
        $this->assertEquals(3, $itemsMoved->count());

        $itemMovedToHero = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->hero);
        });
        $this->assertEquals($itemMovedToHero->id, $this->twoHandedItem->id);
        $this->assertNotNull($itemMovedToHero);
        $this->assertItemTransactionMatches($itemMovedToHero, $this->hero, $this->squad);

        $itemsMovedToSquad = $itemsMoved->filter(function (Item $item) {
            return $item->ownedByMorphable($this->squad);
        });
        $this->assertEquals(2, $itemsMovedToSquad->count());
        $itemsMovedToSquad->each(function (Item $item) use ($itemPreviouslyOnHero, $itemPreviouslyOnHeroTwo) {
            $this->assertTrue(in_array($item->id, [
                $itemPreviouslyOnHero->id,
                $itemPreviouslyOnHeroTwo->id
            ]));
            $this->assertItemTransactionMatches($item, $this->squad, $this->hero);
        });
    }

    /**
     * @test
     */
    public function it_will_replace_only_on_single_hand_items_with_shield()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        /** @var Item $itemPreviouslyOnHeroTwo */
        $itemPreviouslyOnHeroTwo = factory(Item::class)->state('single-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHeroTwo);

        $itemsMoved = $this->domainAction->execute($this->shield, $this->hero);
        $this->assertEquals(2, $itemsMoved->count());

        $itemMovedToHero = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->hero);
        });
        $this->assertNotNull($itemMovedToHero);
        $this->assertEquals($this->shield->id, $itemMovedToHero->id);
        $this->assertItemTransactionMatches($itemMovedToHero, $this->hero, $this->squad);

        $itemMovedToSquad = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->squad);
        });
        $this->assertNotNull($itemMovedToSquad);
        $this->assertEquals($itemPreviouslyOnHeroTwo->id, $itemMovedToSquad->id);
        $this->assertItemTransactionMatches($itemMovedToSquad, $this->squad, $this->hero);

        //Make sure first single-hand item still attached to hero
        $this->assertTrue($itemPreviouslyOnHero->fresh()->ownedByMorphable($this->hero));
    }

    /**
     * @test
     */
    public function it_will_replace_two_hand_item_with_another_two_hand_item()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('two-handed')->create();
        $this->hero->items()->save($itemPreviouslyOnHero);

        $itemsMoved = $this->domainAction->execute($this->twoHandedItem, $this->hero);
        $this->assertEquals(2, $itemsMoved->count());

        $itemMovedToHero = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->hero);
        });
        $this->assertNotNull($itemMovedToHero);
        $this->assertEquals($this->twoHandedItem->id, $itemMovedToHero->id);
        $this->assertItemTransactionMatches($itemMovedToHero, $this->hero, $this->squad);

        $itemMovedToSquad = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->squad);
        });
        $this->assertNotNull($itemMovedToSquad);
        $this->assertEquals($itemPreviouslyOnHero->id, $itemMovedToSquad->id);
        $this->assertItemTransactionMatches($itemMovedToSquad, $this->squad, $this->hero);
    }

    /**
     * @test
     */
    public function it_will_replace_two_hand_item_with_a_shield()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('two-handed')->create();
        $itemPreviouslyOnHero = $itemPreviouslyOnHero->attachToHasItems($this->hero);

        $itemsMoved = $this->domainAction->execute($this->shield, $this->hero);
        $this->assertEquals(2, $itemsMoved->count());

        $itemMovedToHero = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->hero);
        });
        $this->assertNotNull($itemMovedToHero);
        $this->assertEquals($this->shield->id, $itemMovedToHero->id);
        $this->assertItemTransactionMatches($itemMovedToHero, $this->hero, $this->squad);

        $itemMovedToSquad = $itemsMoved->first(function (Item $item) {
            return $item->ownedByMorphable($this->squad);
        });
        $this->assertNotNull($itemMovedToSquad);
        $this->assertEquals($itemPreviouslyOnHero->id, $itemMovedToSquad->id);
        $this->assertItemTransactionMatches($itemMovedToSquad, $this->squad, $this->hero);
    }
}
