<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\ItemBehavior;
use App\Domain\Models\ItemBase;
use App\Domain\Models\MaterialType;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemType
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property int $grade
 *
 * @property ItemBase $itemBase
 */
class ItemType extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materialTypes()
    {
        return $this->belongsToMany(MaterialType::class, 'item_type_material_type', 'i_type_id', 'm_type_id')->withTimestamps();
    }

    public function itemBase()
    {
        return $this->belongsTo(ItemBase::class);
    }

    public function getItemBehavior(): ItemBehavior
    {
        return $this->itemBase->getBehavior();
    }
}
