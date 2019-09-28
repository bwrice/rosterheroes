<?php

namespace Tests\Unit;

use App\Domain\Actions\EmptyHeroSlotAction;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Nova\Hero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmptyHeroSlotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Item */
    protected $item;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var EmptyHeroSlotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->item = factory(Item::class)->create();
        $this->squad = factory(Squad::class)->states('with-slots')->create();

        $this->hero = factory(\App\Domain\Models\Hero::class)->states('with-slots', 'with-measurables')->create();

        factory(HeroPost::class)->create([
            'hero_id' => $this->hero->id,
            'squad_id' => $this->squad->id
        ]);

        $this->domainAction = app(EmptyHeroSlotAction::class);
    }

    /**
     * @test
     * @dataProvider provides_it_will_empty_a_heroes_slot
     * @param $itemBaseName
     */
    public function it_will_empty_a_heroes_slot($itemBaseName)
    {
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseName) {
            return $builder->where('name', '=', $itemBaseName);
        })->first();

        $this->item->item_type_id = $itemType->id;
        $this->item->save();
        $this->item = $this->item->fresh();

        $neededSlotsCount = $this->item->getSlotsCount();
        $heroSlots = $this->hero->getEmptySlots($neededSlotsCount, $this->item->getSlotTypeIDs());
        $this->assertEquals($neededSlotsCount, $heroSlots->count());

        $this->item->slots()->saveMany($heroSlots);
        $this->item = $this->item->fresh();

        $heroSlotToEmpty = $heroSlots->shuffle()->first();
        $this->hero = $this->hero->fresh();

        $this->domainAction->execute($heroSlotToEmpty, $this->hero);

        $this->item = $this->item->fresh();
        $heroSlots = $heroSlots->fresh();
        $heroSlots->each(function (Slot $slot) {
            $this->assertNull($slot->item_id);
        });

        $filledSlots = $this->item->slots;
        $this->assertEquals($neededSlotsCount, $filledSlots->count());
        $filledSlots->each(function (Slot $slot) {
            $hasSlots = $slot->hasSlots;
            $this->assertInstanceOf(Squad::class, $hasSlots);
            $this->assertEquals($slot->has_slots_id, $this->squad->id);
        });
    }

    public function provides_it_will_empty_a_heroes_slot()
    {
        return [
            ItemBase::DAGGER => [
                ItemBase::DAGGER
            ],
            ItemBase::HELMET => [
                ItemBase::HELMET
            ],
            ItemBase::BELT => [
                ItemBase::BELT
            ],
            ItemBase::STAFF => [
                ItemBase::STAFF
            ],
            ItemBase::SHOES => [
                ItemBase::SHOES
            ],
            ItemBase::RING => [
                ItemBase::RING
            ],
            ItemBase::LIGHT_ARMOR => [
                ItemBase::LIGHT_ARMOR
            ],
        ];
    }
}
