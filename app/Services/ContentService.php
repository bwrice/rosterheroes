<?php


namespace App\Services;


use App\Admin\Content\Sources\AttackSource;
use App\Admin\Content\Sources\ItemTypeSource;
use App\Domain\Models\Attack;
use App\Domain\Models\ItemType;
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

    public function itemTypesLastUpdated()
    {
        $dataArray = $this->getItemTypeDataFromJSON();
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
        $itemTypes = ItemType::all();
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

    public function attacksLastUpdated()
    {
        $dataArray = $this->getAttackDataFromJSON();
        return Date::createFromTimestamp($dataArray['last_updated']);
    }
}
