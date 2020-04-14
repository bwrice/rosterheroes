<?php

namespace Tests\Feature;

use App\Domain\Actions\RewardChestToSquad;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBlueprint;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\ItemBlueprintFactory;
use App\Factories\Models\SquadFactory;
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
        $chest = $domainAction->execute($chestBlueprint, $squad);

        $chestGold = $chest->gold;
        $this->assertGreaterThan($chestBlueprint->min_gold, $chestGold);
        $this->assertLessThan($chestBlueprint->max_gold, $chestGold);
        $this->assertEquals($squad->id, $chest->squad_id);
        $this->assertEquals($chestBlueprint->sizeTier, $chest->sizeTier);
        $this->assertEquals($chestBlueprint->qualityTier, $chest->qualityTier);
        $this->assertEquals($chestBlueprint->id, $chest->chest_blueprint_id);
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
        $chest = $domainAction->execute($chestBlueprint, $squad);
        $items = $chest->items;
        $this->assertEquals(1, $items->count());

        /** @var ItemBlueprint $hundredChanceItemBlueprint */
        $hundredChanceItemBlueprint = $chestBlueprint->itemBlueprints->first(function (ItemBlueprint $itemBlueprint) {
            return $itemBlueprint->pivot->chance === 100;
        });

        /** @var Item $item */
        $item = $items->first();

        $this->assertEquals($hundredChanceItemBlueprint->id, $item->item_blueprint_id);
    }
}
