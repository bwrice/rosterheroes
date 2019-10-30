<?php

namespace Tests\Unit;

use App\Domain\Actions\EquipWagonItemForHeroAction;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\ItemTransactionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EquipWagonItemForHeroActionTest extends TestCase
{

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

    /** @var EquipWagonItemForHeroAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->states('with-measurables', 'with-squad')->create();
        $this->squad = $this->hero->getSquad();
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
        $week->everything_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);
        $this->domainAction = app(EquipWagonItemForHeroAction::class);
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
        factory(Week::class)->states('adventuring-closed', 'as-current')->create();

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
        $hasItemsCollection = $this->domainAction->execute($this->randomItem, $this->hero);
        $this->assertEquals(2, $hasItemsCollection->count());

        $hero = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->hero->id && $hasItems->getMorphType() === Hero::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($hero);

        $squadHasItems = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->squad->id && $hasItems->getMorphType() === Squad::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($squadHasItems);
    }

    /**
     * @test
     */
    public function it_will_replace_a_head_item_with_another_head_item()
    {
        /** @var Item $itemPreviouslyOnHero */
        $itemPreviouslyOnHero = factory(Item::class)->state('head')->create();
        $itemPreviouslyOnHero = $itemPreviouslyOnHero->attachToHasItems($this->hero);

        $hasItemsCollection = $this->domainAction->execute($this->headItem, $this->hero);
        $this->assertEquals(2, $hasItemsCollection->count());

        $squad = $itemPreviouslyOnHero->fresh()->hasItems;
        $this->assertEquals($squad->getMorphID(), $this->squad->getMorphID());
        $this->assertEquals($squad->getMorphType(), $this->squad->getMorphType());

        $hero = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->hero->id && $hasItems->getMorphType() === Hero::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($hero);

        $squadHasItems = $hasItemsCollection->first(function (HasItems $hasItems) {
            return $hasItems->getMorphID() === $this->squad->id && $hasItems->getMorphType() === Squad::RELATION_MORPH_MAP_KEY;
        });
        $this->assertNotNull($squadHasItems);
    }
}
