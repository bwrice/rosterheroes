<?php

namespace App;

use App\Slots\Slot;
use App\Slots\Slottable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 *
 * @property ItemType $itemType
 */
class Item extends Model implements Slottable
{
    protected $guarded = [];

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class)->withTimestamps();
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function slots()
    {
        return $this->morphMany(Slot::class, 'slottable');
    }

    public function getSlotTypeIDs(): array
    {
        return $this->itemType->itemBase->slotTypes->pluck('id')->toArray();
    }

    public function getSlotsCount(): int
    {
        return $this->itemType->itemBase->getSlotsCount();
    }
}
