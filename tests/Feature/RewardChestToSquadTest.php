<?php

namespace Tests\Feature;

use App\Domain\Actions\RewardChestToSquad;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\ItemBlueprintFactory;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RewardChestToSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_create_a_chest_for_the_squad_with_the_correct_attributes()
    {
        $squad = SquadFactory::new()->create();
        $chestBlueprint = ChestBlueprintFactory::new()->create();

        /** @var RewardChestToSquad $domainAction */
        $domainAction = app(RewardChestToSquad::class);
        $chest = $domainAction->execute($chestBlueprint, $squad, null);

        $chestGold = $chest->gold;
        $this->assertGreaterThanOrEqual($chestBlueprint->min_gold, $chestGold);
        $this->assertLessThanOrEqual($chestBlueprint->max_gold, $chestGold);
        $this->assertEquals($squad->id, $chest->squad_id);
        $this->assertEquals($chestBlueprint->size, $chest->size);
        $this->assertEquals($chestBlueprint->quality, $chest->quality);
        $this->assertEquals($chestBlueprint->id, $chest->chest_blueprint_id);
        $this->assertEquals($chestBlueprint->description, $chest->description);
        $this->assertNull($chest->opened_at);
    }

    /**
     * @test
     */
    public function it_will_add_items_by_blueprint_chance_to_the_chest()
    {
        $zeroChanceBlueprintFactory = ItemBlueprintFactory::new()->setChestBlueprintChance(0);
        $hundredChanceBlueprintFactory = ItemBlueprintFactory::new()->setChestBlueprintChance(100);

        $squad = SquadFactory::new()->create();
        $chestBlueprint = ChestBlueprintFactory::new()->withItemBlueprints(collect([
            $zeroChanceBlueprintFactory,
            $hundredChanceBlueprintFactory
        ]))->create();

        /** @var RewardChestToSquad $domainAction */
        $domainAction = app(RewardChestToSquad::class);
        $chest = $domainAction->execute($chestBlueprint, $squad, null);
        $items = $chest->items;
        $this->assertEquals(1, $items->count());

        /** @var ItemBlueprint $hundredChanceItemBlueprint */
        $hundredChanceItemBlueprint = $chestBlueprint->itemBlueprints->first(function (ItemBlueprint $itemBlueprint) {
            $chanceDiff = abs($itemBlueprint->pivot->chance - 100);
            return $chanceDiff < PHP_FLOAT_EPSILON;
        });

        /** @var Item $item */
        $item = $items->first();

        $this->assertEquals($hundredChanceItemBlueprint->id, $item->item_blueprint_id);
    }

    /**
     * @test
     */
    public function it_will_add_items_by_blueprint_count_to_chests()
    {
        $singleCountBlueprintFactory = ItemBlueprintFactory::new()
            ->setChestBlueprintCount(1)
            ->setChestBlueprintChance(100);
        $doubleCountBlueprintFactory = ItemBlueprintFactory::new()
            ->setChestBlueprintCount(2)
            ->setChestBlueprintChance(100);

        $squad = SquadFactory::new()->create();
        $chestBlueprint = ChestBlueprintFactory::new()->withItemBlueprints(collect([
            $singleCountBlueprintFactory,
            $doubleCountBlueprintFactory
        ]))->create();

        /** @var RewardChestToSquad $domainAction */
        $domainAction = app(RewardChestToSquad::class);
        $chest = $domainAction->execute($chestBlueprint, $squad, null);
        $items = $chest->items;
        $this->assertEquals(3, $items->count());

        /** @var ItemBlueprint $doubleCountItemBlueprint */
        $doubleCountItemBlueprint = $chestBlueprint->itemBlueprints->first(function (ItemBlueprint $itemBlueprint) {;
            return $itemBlueprint->pivot->count === 2;
        });

        /** @var Collection $item */
        $itemsFromDoubleCountBlueprint = $items->filter(function (Item $item) use ($doubleCountItemBlueprint) {
            return $item->item_blueprint_id === $doubleCountItemBlueprint->id;
        });

        $this->assertEquals(2, $itemsFromDoubleCountBlueprint->count());
    }

    /**
     * @test
     */
    public function it_will_save_the_source_type_when_rewarding_a_chest()
    {
        $squad = SquadFactory::new()->create();
        $chestBlueprint = ChestBlueprintFactory::new()->create();
        $minionSnapshot = MinionSnapshotFactory::new()->create();

        /** @var RewardChestToSquad $domainAction */
        $domainAction = app(RewardChestToSquad::class);
        $chest = $domainAction->execute($chestBlueprint, $squad, $minionSnapshot);
        $this->assertEquals($minionSnapshot->getMorphID(), $chest->source_id);
        $this->assertEquals($minionSnapshot->getMorphType(), $chest->source_type);
    }
}
