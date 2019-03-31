<?php

namespace Tests\Unit;

use App\Domain\Behaviors\ItemBase\ItemBaseBehavior;
use App\Domain\Models\ItemBase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemBaseTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_has_a_behavior()
    {
        $bases = ItemBase::all();

        $bases->each(function (ItemBase $itemBase) {
           $this->assertInstanceOf(ItemBaseBehavior::class, $itemBase->getBehavior(), 'Item Base: ' . $itemBase->name . ' has a behavior' );
        });
    }

    /**
     * @test
     */
    public function it_has_a_slots_count_greater_than_zero()
    {
        $bases = ItemBase::all();

        $bases->each(function (ItemBase $itemBase) {
            $count = $itemBase->getSlotsCount();
            $this->assertTrue(is_integer($count));
            $this->assertGreaterThan(0, $count, 'Slots count greater zero');
        });
    }

    /**
     * @test
     */
    public function it_belongs_to_at_least_one_hero_type()
    {
        $bases = ItemBase::query()->with([
            'slotTypes' => function (BelongsToMany $query) {
                //heroTypes scope on SlotType model
                return $query->heroTypes();
            }])->get();

        $bases->each(function (ItemBase $itemBase) {
           $this->assertGreaterThan(0, $itemBase->slotTypes->count(), $itemBase->name . " belongs to at least 1 hero slot type");
        });
    }
}
