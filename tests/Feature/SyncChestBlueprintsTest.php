<?php

namespace Tests\Feature;

use App\Admin\Content\Actions\SyncChestBlueprints;
use App\Admin\Content\Sources\ChestBlueprintSource;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use App\Facades\Content;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\ItemBlueprintFactory;
use App\Factories\Models\ItemTypeFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class SyncChestBlueprintsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return SyncChestBlueprints
     */
    protected function getDomainAction()
    {
        return app(SyncChestBlueprints::class);
    }

    /**
     * @test
     */
    public function it_will_add_a_missing_chest_blueprint()
    {
        $itemBlueprints = new Collection();

        $itemBlueprintFactory = ItemBlueprintFactory::new();

        $range = rand(1, 5);
        for ($i = 1; $i <= $range; $i++) {
            $itemBlueprints->push($itemBlueprintFactory->create());
        }

        $chestBlueprintSource = new ChestBlueprintSource(
            Str::uuid(),
            'Test Chest Blueprint ' . Str::random(),
            rand(1,6),
            rand(1,6),
            rand(50, 200),
            rand(201, 10000),
            $itemBlueprints->map(function (ItemBlueprint $itemBlueprint) {
                return [
                    'uuid' => $itemBlueprint->uuid,
                    'count' => rand(1,3),
                    'chance' => rand(1,200) * .25
                ];
            })->toArray()
        );

        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedChestBlueprints')->andReturn(collect([$chestBlueprintSource]));

        $this->getDomainAction()->execute();

        /** @var ChestBlueprint $createdChestBlueprint */
        $createdChestBlueprint = ChestBlueprint::query()->where('uuid', '=', $chestBlueprintSource->getUuid())->first();

        $this->assertNotNull($createdChestBlueprint);
        $this->assertEquals($chestBlueprintSource->getDescription(), $createdChestBlueprint->description);
        $this->assertEquals($chestBlueprintSource->getQuality(), $createdChestBlueprint->quality);
        $this->assertEquals($chestBlueprintSource->getSize(), $createdChestBlueprint->size);
        $this->assertEquals($chestBlueprintSource->getMinGold(), $createdChestBlueprint->min_gold);
        $this->assertEquals($chestBlueprintSource->getMaxGold(), $createdChestBlueprint->max_gold);

        $attachedItemBlueprints = $createdChestBlueprint->itemBlueprints;
        $itemBlueprintsFromSource = collect($chestBlueprintSource->getItemBlueprints());
        $this->assertEquals($itemBlueprintsFromSource->count(), $attachedItemBlueprints->count());

        $attachedItemBlueprints->each(function (ItemBlueprint $itemBlueprint) use ($itemBlueprintsFromSource) {
            $match = $itemBlueprintsFromSource->first(function ($itemBlueprintArray) use ($itemBlueprint) {
                return $itemBlueprintArray['uuid'] === (string) $itemBlueprint->uuid;
            });
            $this->assertNotNull($match);
            $this->assertEquals($match['count'], $itemBlueprint->pivot->count);
            $this->assertTrue(abs($match['chance'] - $itemBlueprint->pivot->chance) < PHP_FLOAT_EPSILON);
        });
    }

    /**
     * @test
     */
    public function it_will_update_a_changed_chest_blueprint()
    {
        $chestBlueprint = ChestBlueprintFactory::new()->create();

        $updateChestBlueprintSource = new ChestBlueprintSource(
            $chestBlueprint->uuid,
            Str::random(),
            rand(1,6),
            rand(1,6),
            rand(1,200),
            rand(201, 10000),
            []
        );

        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedChestBlueprints')->andReturn(collect([$updateChestBlueprintSource]));

        $this->getDomainAction()->execute();

        $query = ChestBlueprint::query()->where('uuid', '=', $updateChestBlueprintSource->getUuid());
        $this->assertEquals(1, $query->count());

        /** @var ChestBlueprint $updatedModel */
        $updatedModel = $query->first();
        $this->assertEquals($updateChestBlueprintSource->getDescription(), $updatedModel->description);
        $this->assertEquals($updateChestBlueprintSource->getQuality(), $updatedModel->quality);
        $this->assertEquals($updateChestBlueprintSource->getSize(), $updatedModel->size);
        $this->assertEquals($updateChestBlueprintSource->getMinGold(), $updatedModel->min_gold);
        $this->assertEquals($updateChestBlueprintSource->getMaxGold(), $updatedModel->max_gold);
    }
}
