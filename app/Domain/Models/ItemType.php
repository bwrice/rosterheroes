<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemType
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property int $tier
 *
 * @property ItemBase $itemBase
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
