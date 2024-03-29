<?php

namespace Tests\Feature;

use App\Domain\Actions\Snapshots\BuildAttackSnapshot;
use App\Domain\Actions\Snapshots\BuildItemSnapshot;
use App\Domain\Models\Attack;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemSnapshot;
use App\Domain\Models\ItemType;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\ItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildItemSnapshotTest extends TestCase
{
    use DatabaseTransactions;

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

    /**
     * @test
     */
    public function it_will_create_an_item_snapshot_for_the_hero_snapshot()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withHeroFactory(HeroFactory::new()->withMeasurables())->create();
        // get an item-type without attacks
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->whereIn('name', [
                ItemBase::NECKLACE,
                ItemBase::LIGHT_ARMOR,
                ItemBase::BELT
            ]);
        })->inRandomOrder()->first();
        $item = ItemFactory::new()->withItemType($itemType)->forHero($heroSnapshot->hero)->create();

        $itemSnapshot = $this->getDomainAction()->execute($item, $heroSnapshot);

        $this->assertEquals($heroSnapshot->id, $itemSnapshot->hero_snapshot_id);
        $this->assertEquals($item->id, $itemSnapshot->item_id);
        $this->assertEquals($item->name, $itemSnapshot->name);
        $this->assertEquals($item->item_type_id, $itemSnapshot->item_type_id);
        $this->assertEquals($item->material_id, $itemSnapshot->material_id);
        $this->assertEquals($item->getProtection(), $itemSnapshot->protection);
        $this->assertEquals($item->weight(), $itemSnapshot->weight);
        $this->assertEquals($item->getValue(), $itemSnapshot->value);
        $this->assertTrue(abs($item->getBlockChance() - $itemSnapshot->block_chance) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_execute_build_attack_snapshot_for_each_item_attack()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withHeroFactory(HeroFactory::new()->withMeasurables())->create();
        // get an item-type without attacks
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->whereIn('name', [
                ItemBase::TWO_HAND_AXE,
                ItemBase::BOW,
                ItemBase::WAND
            ]);
        })->inRandomOrder()->first();
        $item = ItemFactory::new()->withItemType($itemType)->forHero($heroSnapshot->hero)->create();

        $itemAttacks = $item->getAttacks();
        $this->assertTrue($itemAttacks->isNotEmpty());

        $itemAttackIDs = $itemAttacks->pluck('id')->values();

        $mock = $this->getMockBuilder(BuildAttackSnapshot::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->exactly($itemAttacks->count()))->method('execute')->with($this->callback(function (Attack $attack) use ($itemAttackIDs) {
            $matchingKey = $itemAttackIDs->search($attack->id);
            if ($matchingKey === false) {
                return false;
            }
            $itemAttackIDs->forget($matchingKey);
            return true;

        }), $this->callback(function (ItemSnapshot $itemSnapshot) use ($item) {
            return $itemSnapshot->item_id === $item->id;
        }), $this->callback(function ($fantasyPower) use ($heroSnapshot) {
            return abs($fantasyPower - $heroSnapshot->fantasy_power) < 0.01;
        }));

        app()->instance(BuildAttackSnapshot::class, $mock);

        $this->getDomainAction()->execute($item, $heroSnapshot);
    }

    /**
     * @test
     */
    public function it_will_attach_the_item_snapshot_to_the_same_enchantments_as_the_item()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withHeroFactory(HeroFactory::new()->withMeasurables())->create();
        // get an item-type without attacks
        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->whereIn('name', [
                ItemBase::NECKLACE,
                ItemBase::LIGHT_ARMOR,
                ItemBase::BELT
            ]);
        })->inRandomOrder()->first();
        $item = ItemFactory::new()->withItemType($itemType)->forHero($heroSnapshot->hero)->withEnchantments()->create();

        $itemEnchantments = $item->enchantments;
        $this->assertTrue($itemEnchantments->isNotEmpty());

        $itemSnapshot = $this->getDomainAction()->execute($item, $heroSnapshot);
        $this->assertEquals(
            $itemEnchantments->sortBy('id')->pluck('id')->values()->toArray(),
            $itemSnapshot->enchantments->sortBy('id')->pluck('id')->values()->toArray()
        );
    }
}
