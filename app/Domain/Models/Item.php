<?php

namespace App\Domain\Models;

use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Behaviors\ItemBase\ItemBaseBehaviorInterface;
use App\Domain\Models\Slot;
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
class Item extends EventSourcedModel implements Slottable
{
    const RELATION_MORPH_MAP = 'items';

    protected $guarded = [];

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class)->withTimestamps();
    }

    public function attacks()
    {
        return $this->belongsToMany(Attack::class)->withTimestamps();
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
        return $this->hasMany(Slot::class);
    }

    public function getSlotTypeIDs(): array
    {
        return $this->getBehavior()->getSlotTypeIDs();
    }

    public function getSlotsCount(): int
    {
        return $this->getBehavior()->getSlotsCount();
    }

    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }

    public function getItemName(): string
    {
        return $this->name ?: $this->buildItemName();
    }

    public function getBehavior(): ItemBaseBehaviorInterface
    {
        return $this->itemType->getItemBehavior();
    }

    protected function buildItemName(): string
    {
        //TODO
        return 'Item';
    }
}
