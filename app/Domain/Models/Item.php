<?php

namespace App\Domain\Models;

use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\StorableEvents\ItemCreated;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 *
 * @property ItemType $itemType
 * @property ItemClass $itemClass
 * @property ItemBlueprint $itemBlueprint
 * @property MaterialType $materialType
 *
 * @property SlotCollection $slots
 * @property EnchantmentCollection $enchantments
 */
class Item extends Model implements Slottable
{
    const RELATION_MORPH_MAP = 'items';

    protected $guarded = [];

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class)->withTimestamps();
    }

    public function itemBlueprint()
    {
        return $this->belongsTo(ItemBlueprint::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function itemClass()
    {
        return $this->belongsTo(ItemClass::class);
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class);
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

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?Item
    {
        return static::where('uuid', $uuid)->first();
    }

    public function getItemName(): string
    {
        return $this->name ?: $this->buildItemName();
    }

    protected function buildItemName(): string
    {
        //TODO
        return 'Item';
    }
}
