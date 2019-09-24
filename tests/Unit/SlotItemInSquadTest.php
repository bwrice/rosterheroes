<?php

namespace Tests\Unit;

use App\Domain\Actions\SlotItemInSquad;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlotItemInSquadTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $item;

    /** @var Squad */
    protected $squad;

    /** @var SlotItemInSquad */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->item = factory(Item::class)->create();
        $this->squad = factory(Squad::class)->states('with-slots')->create();
        $this->domainAction = app(SlotItemInSquad::class);
    }

    /**
     * @test
     * @dataProvider provides_it_will_slot_an_item_on_an_empty_squad
     * @param $itemBaseName
     */
    public function it_will_slot_an_item_on_an_empty_squad($itemBaseName)
    {
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $this->domainAction->execute($this->squad, $this->item);

        $this->item = $this->item->fresh();
        $filledSlots = $this->item->slots;
        $this->assertEquals($this->item->getSlotsCount(), $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $hasSlots->id);
        });
    }

    public function provides_it_will_slot_an_item_on_an_empty_squad()
    {
        return [
            ItemBase::DAGGER => [
                ItemBase::DAGGER
            ]
        ];
    }
}
