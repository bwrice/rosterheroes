<?php

namespace Tests\Unit;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Models\ItemBase;
use App\Domain\Models\SlotType;
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
}
