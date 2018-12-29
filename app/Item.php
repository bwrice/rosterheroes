<?php

namespace App;

use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slottable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 *
 * @property ItemType $itemType
 * @property SlotCollection $slots
 */
class Item extends Model implements Slottable
{
    const RELATION_MORPH_MAP = 'items';

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

    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }
}
