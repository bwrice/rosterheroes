<?php

namespace Tests\Unit;

use App\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_equip_an_item()
    {
        $item = factory(Item::class)->create();

    }
}
