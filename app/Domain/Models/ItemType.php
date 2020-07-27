<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Collections\AttackCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemType
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property int $tier
 * @property int $item_base_id
 *
 * @property ItemBase $itemBase
 *
 * @property AttackCollection $attacks
 */
class ItemType extends Model
{
    protected $guarded = [];

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
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
