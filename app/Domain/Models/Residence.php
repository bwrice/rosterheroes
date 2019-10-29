<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Collections\SlotCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class StoreHouse
 * @package App
 *
 * @property int $id
 * @property int $province_id
 *
 * @property Squad $squad
 * @property Province $province
 * @property ResidenceType $residenceType
 *
 * @property ItemCollection $items
 */
class Residence extends Model implements HasItems
{
    const RELATION_MORPH_MAP_KEY = 'residences';

    protected $guarded = [];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function residenceType()
    {
        return $this->belongsTo(ResidenceType::class);
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'has_items');
    }

    public function getBackupHasItems(): ?HasItems
    {
        return $this->squad->getLocalStash();
    }

    public function hasRoomForItem(Item $item): bool
    {
        return $this->items()->count() < $this->residenceType->getBehavior()->getMaxItemCount();
    }

    public function itemsToMoveForNewItem(Item $item): ItemCollection
    {
        // TODO: Implement itemsToMoveForNewItem() method.
    }

    public function getMorphType(): string
    {
        return static::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }
}
