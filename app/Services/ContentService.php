<?php


namespace App\Services;


use App\Admin\Content\Sources\AttackSource;
use App\Admin\Content\Sources\ChestBlueprintSource;
use App\Admin\Content\Sources\ItemBlueprintSource;
use App\Admin\Content\Sources\ItemTypeSource;
use App\Admin\Content\Sources\MinionSource;
use App\Admin\Content\ViewModels\ContentViewModel;
use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemType;
use App\Domain\Models\Minion;
use Illuminate\Support\Facades\Date;

class ContentService
{
    protected function getAttackDataFromJSON()
    {
        return json_decode(file_get_contents($this->attacksPath()), true);
    }

    public function attacksPath()
    {
        return resource_path('json/content/attacks.json');
    }

    public function attacks()
    {
        $dataArray = $this->getAttackDataFromJSON();

        return collect($dataArray['data'])->map(function ($attackData) {

            return new AttackSource(
                $attackData['uuid'],
                $attackData['name'],
                $attackData['attacker_position_id'],
                $attackData['target_position_id'],
                $attackData['target_priority_id'],
                $attackData['damage_type_id'],
                $attackData['tier'],
                $attackData['targets_count'],
            );
        });
    }

    public function unSyncedAttacks()
    {
        $attackSources = $this->attacks();
        $attacks = Attack::all();
        return $attackSources->filter(function (AttackSource $attackSource) use ($attacks) {
            $match = $attacks->first(function (Attack $attack) use ($attackSource) {
                return $attackSource->getUuid() === (string) $attack->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $attackSource->isSynced($match);
        });
    }

    public function attacksLastUpdated()
    {
        $dataArray = $this->getAttackDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }

    protected function getItemTypeDataFromJSON()
    {
        return json_decode(file_get_contents($this->itemTypesPath()), true);
    }

    public function itemTypesPath()
    {
        return resource_path('json/content/item_types.json');
    }

    public function itemTypes()
    {
        $dataArray = $this->getItemTypeDataFromJSON();

        return collect($dataArray['data'])->map(function ($itemTypeData) {

            return new ItemTypeSource(
                $itemTypeData['uuid'],
                $itemTypeData['name'],
                $itemTypeData['tier'],
                $itemTypeData['item_base_id'],
                $itemTypeData['attacks']
            );
        });
    }

    public function unSyncedItemTypes()
    {
        $itemTypeSources = $this->itemTypes();
        $itemTypes = ItemType::query()->with('attacks')->get();
        return $itemTypeSources->filter(function (ItemTypeSource $itemTypeSource) use ($itemTypes) {
            $match = $itemTypes->first(function (ItemType $itemType) use ($itemTypeSource) {
                return $itemTypeSource->getUuid() === (string) $itemType->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $itemTypeSource->isSynced($match);
        });
    }

    public function itemTypesLastUpdated()
    {
        $dataArray = $this->getItemTypeDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }

    protected function getItemBlueprintsDataFromJSON()
    {
        return json_decode(file_get_contents($this->itemBlueprintsPath()), true);
    }

    public function itemBlueprintsPath()
    {
        return resource_path('json/content/item_blueprints.json');
    }

    public function itemBlueprints()
    {
        $dataArray = $this->getItemBlueprintsDataFromJSON();

        return collect($dataArray['data'])->map(function ($itemBlueprintData) {

            return new ItemBlueprintSource(
                $itemBlueprintData['uuid'],
                $itemBlueprintData['item_name'],
                $itemBlueprintData['description'],
                $itemBlueprintData['enchantment_power'],
                $itemBlueprintData['item_bases'],
                $itemBlueprintData['item_classes'],
                $itemBlueprintData['item_types'],
                $itemBlueprintData['attacks'],
                $itemBlueprintData['materials'],
                $itemBlueprintData['enchantments']
            );
        });
    }

    public function unSyncedItemBlueprints()
    {
        $itemBlueprintSources = $this->itemBlueprints();
        $itemBlueprints = ItemBlueprint::query()->with([
            'itemBases',
            'itemClasses',
            'itemTypes',
            'attacks',
            'materials',
            'enchantments'
        ])->get();
        return $itemBlueprintSources->filter(function (ItemBlueprintSource $itemBlueprintSource) use ($itemBlueprints) {
            $match = $itemBlueprints->first(function (ItemBlueprint $itemBlueprint) use ($itemBlueprintSource) {
                return $itemBlueprintSource->getUuid() === (string) $itemBlueprint->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $itemBlueprintSource->isSynced($match);
        });
    }

    public function itemBlueprintsLastUpdated()
    {
        $dataArray = $this->getItemBlueprintsDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }

    protected function getChestBlueprintsDataFromJSON()
    {
        return json_decode(file_get_contents($this->chestBlueprintsPath()), true);
    }

    public function chestBlueprintsPath()
    {
        return resource_path('json/content/chest_blueprints.json');
    }

    public function chestBlueprints()
    {
        $dataArray = $this->getChestBlueprintsDataFromJSON();

        return collect($dataArray['data'])->map(function ($itemBlueprintData) {

            return new ChestBlueprintSource(
                $itemBlueprintData['uuid'],
                $itemBlueprintData['description'],
                $itemBlueprintData['quality'],
                $itemBlueprintData['size'],
                $itemBlueprintData['min_gold'],
                $itemBlueprintData['max_gold'],
                $itemBlueprintData['item_blueprints']
            );
        });
    }

    public function unSyncedChestBlueprints()
    {
        $itemBlueprintSources = $this->chestBlueprints();
        $chestBlueprints = ChestBlueprint::query()->with([
            'itemBlueprints'
        ])->get();
        return $itemBlueprintSources->filter(function (ChestBlueprintSource $chestBlueprintSource) use ($chestBlueprints) {
            $match = $chestBlueprints->first(function (ChestBlueprint $chestBlueprint) use ($chestBlueprintSource) {
                return $chestBlueprintSource->getUuid() === (string) $chestBlueprint->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $chestBlueprintSource->isSynced($match);
        });
    }

    public function chestBlueprintsLastUpdated()
    {
        $dataArray = $this->getMinionsDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }

    protected function getMinionsDataFromJSON()
    {
        return json_decode(file_get_contents($this->minionsPath()), true);
    }

    public function minionsPath()
    {
        return resource_path('json/content/minions.json');
    }

    public function minions()
    {
        $dataArray = $this->getMinionsDataFromJSON();

        return collect($dataArray['data'])->map(function ($itemBlueprintData) {

            return new MinionSource(
                $itemBlueprintData['uuid'],
                $itemBlueprintData['name'],
                $itemBlueprintData['level'],
                $itemBlueprintData['enemy_type'],
                $itemBlueprintData['combat_position'],
                $itemBlueprintData['attacks'],
                $itemBlueprintData['chest_blueprints']
            );
        });
    }

    public function unSyncedMinions()
    {
        $minionSources = $this->minions();
        $minions = Minion::query()->with([
            'attacks',
            'chestBlueprints'
        ])->get();
        return $minionSources->filter(function (MinionSource $minionSource) use ($minions) {
            $match = $minions->first(function (Minion $minion) use ($minionSource) {
                return $minionSource->getUuid() === (string) $minion->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $minionSource->isSynced($match);
        });
    }

    public function minionsLastUpdated()
    {
        $dataArray = $this->getMinionsDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }

    public function viewURL(ContentViewModel $viewModel)
    {
        return url('/admin/content/' . str_replace(' ', '-', strtolower($viewModel->getTitle())));
    }

    public function createURL(ContentViewModel $viewModel)
    {
        return url('/admin/content/' . str_replace(' ', '-', strtolower($viewModel->getTitle())) . '/create');
    }

    public function syncURL(ContentViewModel $viewModel)
    {
        return url('/admin/content/' . str_replace(' ', '-', strtolower($viewModel->getTitle())) . '/sync');
    }
}
