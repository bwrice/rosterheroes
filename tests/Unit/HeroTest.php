<?php

namespace Tests\Unit;

use App\Hero;
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
        /** @var Item $item */
        $item = factory(Item::class)->create();
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        $this->assertGreaterThan(0, $hero->slots()->count(), 'Hero has slots');

        $hero->equip($item);

        $hero->hasEquipped($item);
    }
}
