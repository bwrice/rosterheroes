<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Http\Resources\GlobalStashResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * Class Stash
 * @package App
 *
 * @property int $id
 * @property string $uuid;
 * @property int $squad_id
 * @property int $province_id
 *
 * @property Squad $squad
 * @property Province $province
 *
 * @property ItemCollection $items
 */
class Stash extends Model implements HasItems
{
    public const RELATION_MORPH_MAP_KEY = 'stashes';

    protected $guarded = [];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

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
        // Stash is last resort for Squad storing items. Always true.
        return true;
    }

    public function itemsToMoveForNewItem(Item $item): ItemCollection
    {
        /*
         * We won't ever have to move items for a stash.
         * They're unlimited, so we'll return any empty collection
         */
        return new ItemCollection();
    }

    public function getMorphType(): string
    {
        return static::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }

    public static function getResourceRelations()
    {
        return [
            'items.itemType.itemBase',
            'items.material.materialType',
            'items.itemClass',
            'items.attacks.attackerPosition',
            'items.attacks.targetPosition',
            'items.attacks.targetPriority',
            'items.attacks.damageType',
            'items.enchantments.measurableBoosts.measurableType',
            'items.enchantments.measurableBoosts.booster',
        ];
    }

    public function getTransactionIdentification(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->getMorphType()
        ];
    }
}
