<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\ChestBlueprintSource;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use App\Facades\Content;
use Illuminate\Support\Facades\DB;

class SyncChestBlueprints extends SyncContent
{
    protected $dependencies = [
        self::ITEM_BLUEPRINTS_DEPENDENCY
    ];

    public function execute()
    {
        $this->checkDependencies();

        $unSyncedSources = Content::unSyncedChestBlueprints();
        $notSynced = collect();

        $unSyncedSources->each(function (ChestBlueprintSource $chestBlueprintSource) use ($notSynced) {

            try {

                DB::transaction(function () use ($chestBlueprintSource, $notSynced) {

                    /** @var ChestBlueprint $chestBlueprint */
                    $chestBlueprint = ChestBlueprint::query()->updateOrCreate([
                        'uuid' => $chestBlueprintSource->getUuid()
                    ], [
                        'description' => $chestBlueprintSource->getDescription(),
                        'quality' => $chestBlueprintSource->getQuality(),
                        'size' => $chestBlueprintSource->getSize(),
                        'min_gold' => $chestBlueprintSource->getMinGold(),
                        'max_gold' => $chestBlueprintSource->getMaxGold()
                    ]);

                    // Query for item blueprints
                    $itemBlueprintArrays = collect($chestBlueprintSource->getItemBlueprints());
                    $uuids = $itemBlueprintArrays->map(function ($blueprintArray) {
                        return $blueprintArray['uuid'];
                    })->toArray();

                    $itemBlueprints = ItemBlueprint::query()->whereIn('uuid', $uuids)->get();

                    // Verify we have the correct amount of item blueprints
                    if ($itemBlueprints->count() !== $itemBlueprintArrays->count()) {
                        throw new \Exception("Not all item-blueprints found for chest-blueprint: " . $chestBlueprint->uuid);
                    }

                    // Attach item blueprints with count and chance pivot values
                    $syncArray = $itemBlueprintArrays->mapWithKeys(function ($itemBlueprintArray) use ($itemBlueprints, $chestBlueprint) {
                        /** @var ItemBlueprint $itemBlueprint */
                        $itemBlueprint = $itemBlueprints->firstWhere('uuid', '=', $itemBlueprintArray['uuid']);

                        return [$itemBlueprint->id => [
                            'count' => $itemBlueprintArray['count'],
                            'chance' => $itemBlueprintArray['chance']
                        ]];
                    });

                    $chestBlueprint->itemBlueprints()->sync($syncArray);
                });

            } catch (\Exception $exception) {
                $notSynced->push([
                    'source' => $chestBlueprintSource,
                    'exception' => $exception
                ]);
            }
        });

        return $notSynced;
    }
}
