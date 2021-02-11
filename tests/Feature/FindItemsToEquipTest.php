<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindItemsForHeroToEquip;
use App\Domain\Actions\NPC\FindItemsToEquip;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FindItemsToEquipTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindItemsToEquip
     */
    protected function getDomainAction()
    {
        return app(FindItemsToEquip::class);
    }

    /**
     * @test
     */
    public function it_will_execute_find_items_for_hero_to_equip_for_each_hero()
    {
        $squad = SquadFactory::new()->create();

        $factory = HeroFactory::new()->forSquad($squad);
        $count = rand(2, 4);
        for ($i = 1; $i <= $count; $i++) {
            $factory->create();
        }

        $mock = \Mockery::mock(FindItemsForHeroToEquip::class)
            ->shouldReceive('execute')
            ->times($count)
            ->getMock();
        $this->instance(FindItemsForHeroToEquip::class, $mock);

        $this->getDomainAction()->execute($squad);
    }

    /**
     * @test
     */
    public function it_will_return_collection_of_equip_arrays_for_each_hero()
    {
        $squad = SquadFactory::new()->create();
        $heroFactory = HeroFactory::new()->forSquad($squad);
        $itemFactory = ItemFactory::new()->forSquad($squad);

        $heroA = $heroFactory->create();
        $heroAItems = collect([
            $itemFactory->create(),
            $itemFactory->create()
        ]);
        $heroB = $heroFactory->create();
        $heroBItems = collect([
            $itemFactory->create(),
            $itemFactory->create(),
            $itemFactory->create()
        ]);

        $mock = \Mockery::mock(FindItemsForHeroToEquip::class)
            ->shouldReceive('execute')
            ->andReturn($heroAItems, $heroBItems)
            ->getMock();
        $this->instance(FindItemsForHeroToEquip::class, $mock);

        $equipArrays = $this->getDomainAction()->execute($squad);
        $this->assertEquals(2, $equipArrays->count());

        $heroAEquipArray = $equipArrays->first(function ($equipArray) use ($heroA) {
            return $equipArray['hero']->id === $heroA->id;
        });
        $this->assertNotNull($heroAEquipArray);
        /** @var Collection $equipItemsForHeroA */
        $equipItemsForHeroA = $heroAEquipArray['items'];
        $this->assertArrayElementsEqual($heroAItems->pluck('id')->toArray(), $equipItemsForHeroA->pluck('id')->toArray());

        $heroBEquipArray = $equipArrays->first(function ($equipArray) use ($heroB) {
            return $equipArray['hero']->id === $heroB->id;
        });
        $this->assertNotNull($heroBEquipArray);
        /** @var Collection $equipItemsForHeroB */
        $equipItemsForHeroB = $heroBEquipArray['items'];
        $this->assertArrayElementsEqual($heroBItems->pluck('id')->toArray(), $equipItemsForHeroB->pluck('id')->toArray());
    }
}
