<?php


namespace App\Domain\Actions;


use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Exceptions\CreateItemBlueprintException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CreateItemBlueprint
{
    /**
     * @param string $referenceID
     * @param null $description
     * @param array $itemTypeNames
     * @param array $itemClassNames
     * @param array $materialNames
     * @param array $itemBaseNames
     * @param int $enchantmentPower
     * @param string|null $itemName
     * @throws CreateItemBlueprintException
     *
     * @return ItemBlueprint
     */
    public function execute(
        string $referenceID,
        $description = null,
        array $itemTypeNames = [],
        array $materialNames = [],
        array $itemClassNames = [],
        array $itemBaseNames = [],
        int $enchantmentPower = null,
        string $itemName = null)
    {
        $itemTypes = $this->getItemTypes($itemTypeNames);
        $materials = $this->getMaterials($materialNames);
        $itemClasses = $this->getItemClasses($itemClassNames);

        $itemBases = $itemTypes->isEmpty() ? $this->getItemBases($itemBaseNames) : [];

        /** @var ItemBlueprint $itemBlueprint */
        $itemBlueprint = ItemBlueprint::query()->create([
            'reference_id' => $referenceID,
            'description' => $description,
            'item_name' => $itemName,
            'enchantment_power' => $enchantmentPower
        ]);

        $itemBlueprint->itemTypes()->saveMany($itemTypes);
        $itemBlueprint->materials()->saveMany($materials);
        $itemBlueprint->itemClasses()->saveMany($itemClasses);
        $itemBlueprint->itemBases()->saveMany($itemBases);

        return $itemBlueprint;
    }

    /**
     * @param array $itemTypeNames
     * @return Collection
     * @throws CreateItemBlueprintException
     */
    protected function getItemTypes(array $itemTypeNames)
    {
        return $this->getReferenceModels(
            ItemType::query(),
            $itemTypeNames,
            'item-type',
            CreateItemBlueprintException::CODE_INVALID_ITEM_TYPES);
    }

    /**
     * @param array $materialNames
     * @return Collection
     * @throws CreateItemBlueprintException
     */
    protected function getMaterials(array $materialNames)
    {
        return $this->getReferenceModels(
            Material::query(),
            $materialNames,
            'material',
            CreateItemBlueprintException::CODE_INVALID_MATERIALS);
    }

    /**
     * @param array $itemClassNames
     * @return Collection
     * @throws CreateItemBlueprintException
     */
    protected function getItemClasses(array $itemClassNames)
    {
        return $this->getReferenceModels(
            ItemClass::query(),
            $itemClassNames,
            'item-class',
            CreateItemBlueprintException::CODE_INVALID_ITEM_CLASSES);
    }

    /**
     * @param array $itemBaseNames
     * @return Collection
     * @throws CreateItemBlueprintException
     */
    protected function getItemBases(array $itemBaseNames)
    {
        return $this->getReferenceModels(
            ItemBase::query(),
            $itemBaseNames,
            'item-base',
            CreateItemBlueprintException::CODE_INVALID_ITEM_BASES);
    }

    /**
     * @param Builder $baseQuery
     * @param array $names
     * @param string $referenceName
     * @param int $exceptionCode
     * @return Collection
     * @throws CreateItemBlueprintException
     */
    protected function getReferenceModels(Builder $baseQuery, array $names, string $referenceName, int $exceptionCode)
    {
        $referenceModels = $baseQuery->whereIn('name', $names)->get();

        if ($referenceModels->count() !== count($names)) {
            $namesArray = $referenceModels->map(function ($referenceModel) {
                return $referenceModel->name;
            })->toArray();
            $invalid = collect($names)->first(function ($name) use ($namesArray) {
                return ! in_array($name, $namesArray);
            });
            $message = "Invalid " . $referenceName . " " . $invalid . " when creating item blueprint";
            throw new CreateItemBlueprintException($message, $exceptionCode);
        }
        return $referenceModels;
    }
}
