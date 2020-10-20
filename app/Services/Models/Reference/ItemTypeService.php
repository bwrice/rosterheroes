<?php


namespace App\Services\Models\Reference;


use App\Domain\Models\ItemType;
use App\Facades\ItemBaseFacade;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ItemTypeService
 * @package App\Services\Models\Reference
 *
 * @method ItemType getReferenceModelByID(int $id)
 */
class ItemTypeService extends ReferenceService
{

    protected function all(): Collection
    {
        return ItemType::all();
    }

    public function tier(int $itemTypeID)
    {
        return $this->getReferenceModelByID($itemTypeID)->tier;
    }

    public function baseDamageBonus(int $itemTypeID)
    {
        return $this->tier($itemTypeID)/25;
    }

    public function baseBehavior(int $itemTypeID)
    {
        $itemType = $this->getReferenceModelByID($itemTypeID);
        return ItemBaseFacade::getBehavior($itemType->item_base_id);
    }
}
