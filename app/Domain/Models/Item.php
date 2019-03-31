<?php

namespace App\Domain\Models;

use App\Domain\Models\Enchantment;
use App\Events\ItemCreated;
use App\Events\ItemCreationRequested;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\MaterialType;
use App\Domain\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Ramsey\Uuid\Uuid;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 *
 * @property ItemType $itemType
 * @property ItemClass $itemClass
 * @property ItemBlueprint $itemBlueprint
 * @property MaterialType $materialType
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

    /**
     * @param ItemClass $itemClass
     * @param ItemType $itemType
     * @param MaterialType $materialType
     * @param ItemBlueprint $itemBlueprint
     * @return Item|null
     * @throws \Exception
     */
    public static function generate(ItemClass $itemClass, ItemType $itemType, MaterialType $materialType, ItemBlueprint $itemBlueprint)
    {
        $item = self::createWithAttributes([
            'item_class_id' => $itemClass->id,
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id,
            'item_blueprint_id' => $itemBlueprint->id,
            'name' => $itemBlueprint->item_name
        ]);

        event(new ItemCreated($item));

        return $item;
    }

    /**
     * @param array $attributes
     * @return Item|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new ItemCreationRequested($attributes));

        return self::uuid($uuid);
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?Item
    {
        return static::where('uuid', $uuid)->first();
    }
}
