<?php

namespace Tests\Unit;

use App\Items\ItemBases\ItemBase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemBaseTest extends TestCase
{
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
