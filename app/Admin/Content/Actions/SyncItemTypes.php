<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\ItemTypeSource;
use App\Domain\Models\Attack;
use App\Domain\Models\ItemType;
use App\Facades\Content;
use Illuminate\Support\Facades\DB;

class SyncItemTypes extends SyncContent
{
    protected $dependencies = [
        self::ATTACKS_DEPENDENCY
    ];

    /**
     * @return mixed
     * @throws \App\Exceptions\SyncContentException
     */
    public function execute()
    {
        $this->checkDependencies();

        $unSyncedSources = Content::unSyncedItemTypes();
        $notSynced = collect();

        $unSyncedSources->each(function (ItemTypeSource $itemTypeSource) use ($notSynced) {

            try {

                DB::transaction(function () use ($itemTypeSource, $notSynced) {


                    /** @var ItemType $itemType */
                    $itemType = ItemType::query()->updateOrCreate([
                        'uuid' => $itemTypeSource->getUuid()
                    ], [
                        'name' => $itemTypeSource->getName(),
                        'tier' => $itemTypeSource->getTier(),
                        'item_base_id' => $itemTypeSource->getItemBaseID()
                    ]);

                    $attacks = Attack::query()->whereIn('uuid', $itemTypeSource->getAttackUuids())->get();

                    if ($attacks->count() !== count($itemTypeSource->getAttackUuids())) {
                        throw new \Exception("Not all attacks found for item-type: " . $itemType->name);
                    }

                    $itemType->attacks()->sync($attacks->pluck('id')->toArray());
                });

            } catch (\Exception $exception) {
                $notSynced->push([
                    'source' => $itemTypeSource,
                    'exception' => $exception
                ]);
            }
        });

        return $notSynced;
    }
}
