<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildItemSnapshot;
use App\Domain\Models\Item;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemFactory;
use App\HeroSnapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildItemSnapshotTest extends TestCase
{
    /**
     * @return BuildItemSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildItemSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_item_and_hero_snapshot_dont_belong_to_the_same_hero()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->create();

        $diffHero = HeroFactory::new()->create();
        $item = ItemFactory::new()->create();
        $item->hasItems()->associate($diffHero);
        $item->save();

        try {
            $this->getDomainAction()->execute($item, $heroSnapshot);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildItemSnapshot::EXCEPTION_CODE_HERO_MISMATCH, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
