<?php


namespace App\Services\Models\Reference;


use App\Domain\Models\ItemType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ItemTypeService
 * @package App\Services\Models\Reference
 *
 * @method  ItemType getReferenceModelByID(int $id)
 */
class ItemTypeService extends ReferenceService
{

    protected function all(): Collection
    {
        return ItemType::all();
    }

    public function tier(int $id)
    {
        return $this->getReferenceModelByID($id)->tier;
    }
}
