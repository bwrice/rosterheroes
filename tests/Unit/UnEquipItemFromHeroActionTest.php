<?php

namespace Tests\Unit;

use App\Domain\Actions\UnEquipItemFromHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnEquipItemFromHeroActionTest extends TestCase
{
    /** @var Hero */
    protected $hero;

    /** @var Item */
    protected $item;

    /** @var UnEquipItemFromHeroAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->states('with-measurables', 'with-squad')->create();
        $this->item = factory(Item::class)->create([
            'has_items_type' => Hero::RELATION_MORPH_MAP_KEY,
            'has_items_id' => $this->hero->id
        ]);
        $this->domainAction = app(UnEquipItemFromHeroAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_anyone()
    {
        $this->item->has_items_id = null;
        $this->item->has_items_type = null;
        $this->item->save();

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
    public function it_will_throw_an_exception_if_the_item_does_not_belong_to_the_hero()
    {
        $hero = factory(Hero::class)->create();
        $this->item->has_items_id = $hero->id;
        $this->item->has_items_type = Hero::RELATION_MORPH_MAP_KEY;
        $this->item->save();

        try {
            $this->domainAction->execute($this->item->fresh(), $this->hero);

        } catch (ItemTransactionException $exception) {
            $this->assertEquals(ItemTransactionException::CODE_INVALID_OWNERSHIP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
