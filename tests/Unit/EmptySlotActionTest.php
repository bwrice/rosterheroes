<?php

namespace Tests\Unit;

use App\Domain\Actions\EmptySlotAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmptySlotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var EmptySlotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->hero = factory(Hero::class)->states('with-slots', 'with-measurables')->create();
        $this->squad = factory(Squad::class)->states('with-slots')->create();

        factory(HeroPost::class)->create([
            'hero_id' => $this->hero->id,
            'squad_id' => $this->squad->id
        ]);

        $this->domainAction = app(EmptySlotAction::class);
    }

    /**
     * @test
     */
    public function it_will_empty_a_single_slot_from_a_hero_attached_to_a_squad()
    {
        $helmetType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            return $builder->where('name', '=', ItemBase::HELMET);
        })->first();

        /** @var Item $helmetItem */
        $helmetItem = factory(Item::class)->create([
            'item_type_id' => $helmetType->id
        ]);

        /** @var Slot $headSlot */
        $headSlot = $this->hero->slots->first(function (Slot $slot) {
            return $slot->slotType->name === SlotType::HEAD;
        });

        $helmetItem->slots()->save($headSlot);

        $this->assertEquals($headSlot->item_id, $helmetItem->id);

        $this->domainAction->execute($headSlot);

        $headSlot = $headSlot->fresh();
        $this->assertNull($headSlot->item_id);

        $item = $helmetItem->fresh();
        $this->assertEquals(1, $item->slots->count());
        /** @var Slot $squadSlot */
        $squadSlot = $item->slots->first();
        $this->assertTrue($squadSlot->hasSlots instanceof Squad);
        $this->assertEquals($squadSlot->hasSlots->id, $this->squad->id);
    }
}
