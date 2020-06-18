<?php

namespace App\Domain\Models;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\Merchant;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shop
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $province_id
 * @property string $name
 * @property string $slug
 * @property int $tier
 *
 * @property Province $province
 *
 * @property ItemCollection $items
 * @property ItemCollection $availableItems
 */
class Shop extends Model implements HasItems, Merchant
{
    use HasNameSlug;
    use HasUuid;
    use HasUniqueNames;

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

    public function availableItems()
    {
        return $this->items()->where('made_shop_available_at', '!=', null);
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

    public static function getResourceRelations()
    {
        return array_map(function ($relation) {
            return 'availableItems.' . $relation;
        }, Item::resourceRelations());
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getMerchantType(): string
    {
        return 'shop';
    }
}
