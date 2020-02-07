<?php


namespace App\Factories\Models;


use App\Domain\Models\Item;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use Illuminate\Support\Str;

class ItemFactory
{
    /** @var int */
    protected $itemClassID;

    /** @var ItemType */
    protected $itemType;

    /** @var int */
    protected $materialTypeID;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $itemType = $this->itemType ?: ItemType::query()->inRandomOrder()->first();

        /** @var Item $item */
        $item = Item::query()->create([
            'uuid' => (string) Str::uuid(),
            'item_class_id' => $this->itemClassID ?: ItemClass::generic()->id,
            'item_type_id' => $itemType->id,
            'material_type_id' => $this->materialTypeID ?: $this->itemType->materials()->inRandomOrder()->first()->id
        ]);

        return $item;
    }
}
