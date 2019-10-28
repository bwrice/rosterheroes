<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemBases\ItemBaseBehaviorInterface;
use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;
use App\Domain\Interfaces\AdjustsDamageModifier;
use App\Domain\Models\ItemBase;
use App\Domain\Models\Material;
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
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'item_type_material', 'i_type_id', 'material_id')->withTimestamps();
    }

    public function itemBase()
    {
        return $this->belongsTo(ItemBase::class);
    }

    public function getItemBaseBehavior(): ItemBaseBehavior
    {
        return $this->itemBase->getBehavior();
    }
}
