<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shop
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $province_id
 *
 * @property Province $province
 *
 * @property ItemCollection $items
 */
class Shop extends Model implements HasItems
{
    public const RELATION_MORPH_MAP_KEY = 'shops';

    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'has_items');
    }

    public function getBackupHasItems(): ?HasItems
    {
        return null;
    }

    public function hasRoomForItem(Item $item): bool
    {
        return true;
    }

    public function itemsToMoveForNewItem(Item $item): ItemCollection
    {
        return new ItemCollection();
    }

    public function getTransactionIdentification(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->getMorphType()
        ];
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
