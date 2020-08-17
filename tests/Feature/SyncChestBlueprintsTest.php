<?php

namespace Tests\Feature;

use App\Admin\Content\Actions\SyncChestBlueprints;
use App\Admin\Content\Sources\ChestBlueprintSource;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use App\Exceptions\SyncContentException;
use App\Facades\Content;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\ItemBlueprintFactory;
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

    /**
     * @test
     */
    public function it_will_sync_item_blueprints_based_on_the_chest_blueprint_source()
    {
        $itemBlueprintFactory = ItemBlueprintFactory::new();

        $unchangedItemBlueprint = $itemBlueprintFactory->create();
        $itemBlueprintToRemove = $itemBlueprintFactory->create();

        $chestBlueprint = ChestBlueprintFactory::new()->create();

        $chestBlueprint->itemBlueprints()->save($unchangedItemBlueprint, [
            'count' => $unchangedCount = rand(1,3),
            'chance' => $unchangedChance = rand(1,100) * .5
        ]);
        $chestBlueprint->itemBlueprints()->save($itemBlueprintToRemove, [
            'count' => rand(1,3),
            'chance' => rand(1,100) * .5
        ]);

        $newlyAddedItemBlueprint = $itemBlueprintFactory->create();

        $updatedSource = new ChestBlueprintSource(
            $chestBlueprint->uuid,
            $chestBlueprint->description,
            $chestBlueprint->quality,
            $chestBlueprint->size,
            $chestBlueprint->min_gold,
            $chestBlueprint->max_gold,
            [
                [
                    'uuid' => $unchangedItemBlueprint->uuid,
                    'count' => $unchangedCount,
                    'chance' => $unchangedChance
                ],
                [
                    'uuid' => $newlyAddedItemBlueprint->uuid,
                    'count' => $newlyAddedCount = rand(1, 3),
                    'chance' => $newlyAddedChance = rand(1, 100) * .5
                ],
            ]
        );

        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedChestBlueprints')->andReturn(collect([$updatedSource]));

        $this->getDomainAction()->execute();

        $query = ChestBlueprint::query()->where('uuid', '=', $updatedSource->getUuid());
        $this->assertEquals(1, $query->count());

        /** @var ChestBlueprint $updatedModel */
        $updatedModel = $query->first();

        $itemBlueprintArrays = collect($updatedSource->getItemBlueprints());

        $attachedItemBlueprints = $updatedModel->itemBlueprints;
        $this->assertEquals($itemBlueprintArrays->count(), $attachedItemBlueprints->count());

        /** @var ItemBlueprint $unchangedMatch */
        $unchangedMatch = $attachedItemBlueprints->firstWhere('uuid', '=', $unchangedItemBlueprint->uuid);
        $this->assertNotNull($unchangedMatch);
        $this->assertEquals($unchangedCount, $unchangedMatch->pivot->count);
        $this->assertTrue(abs($unchangedMatch->pivot->chance - $unchangedChance) < PHP_FLOAT_EPSILON);

        /** @var ItemBlueprint $newlyAddedMatch */
        $newlyAddedMatch = $attachedItemBlueprints->firstWhere('uuid', '=', $newlyAddedItemBlueprint->uuid);
        $this->assertNotNull($newlyAddedMatch);
        $this->assertEquals($newlyAddedCount, $newlyAddedMatch->pivot->count);
        $this->assertTrue(abs($newlyAddedMatch->pivot->chance - $newlyAddedChance) < PHP_FLOAT_EPSILON);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_when_syncing_chest_blueprints_if_the_item_blueprints_are_out_of_sync()
    {

        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect(['anything']));

        try {
            $this->getDomainAction()->execute();
        } catch (SyncContentException $syncContentException) {
            $this->assertEquals(SyncContentException::CODE_ITEM_BLUEPRINTS_NOT_SYNCED, $syncContentException->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_sync_chest_blueprints_while_returning_sources_that_failed()
    {
        $unknownItemBlueprintUuid = Str::uuid();

        $failedSource = new ChestBlueprintSource(
            Str::uuid(),
            'Test Chest Blueprint ' . Str::random(),
            rand(1,6),
            rand(1,6),
            rand(50, 200),
            rand(201, 10000),
            [
                [
                'uuid' => $unknownItemBlueprintUuid,
                'count' => rand(1, 3),
                'chance' => rand(1, 200) * .25
                ]
            ]
        );

        $successfulSource = new ChestBlueprintSource(
            Str::uuid(),
            'Test Chest Blueprint ' . Str::random(),
            rand(1,6),
            rand(1,6),
            rand(50, 200),
            rand(201, 10000),
            []
        );

        Content::partialMock()->shouldReceive('unSyncedItemBlueprints')->andReturn(collect());
        Content::partialMock()->shouldReceive('unSyncedChestBlueprints')->andReturn(collect([$failedSource, $successfulSource]));

        $failedSources = $this->getDomainAction()->execute();
        $this->assertEquals(1, $failedSources->count());
        $this->assertEquals($failedSource->getUuid(), $failedSources->first()['source']->getUuid());
    }
}
