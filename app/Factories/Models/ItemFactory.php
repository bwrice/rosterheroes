<?php


namespace App\Factories\Models;


use App\Domain\Models\Enchantment;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemFactory
{
    /** @var int */
    protected $itemClassID;

    /** @var ItemType */
    protected $itemType;

    /** @var int */
    protected $materialTypeID;

    /** @var array */
    protected $hasItems = [];

    /** @var Collection */
    protected $enchantments;

    /** @var Collection */
    protected $attacks;

    public function __construct()
    {
        $this->enchantments = collect();
        $this->attacks = collect();
    }

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $itemType = $this->itemType ?: ItemType::query()->inRandomOrder()->first();

        /** @var Item $item */
        $item = Item::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'item_class_id' => $this->itemClassID ?: ItemClass::generic()->id,
            'item_type_id' => $itemType->id,
            'material_id' => $this->materialTypeID ?: $itemType->materials()->inRandomOrder()->first()->id,
            'has_items_id' => $this->hasItems['id'] ?? null,
            'has_items_type' => $this->hasItems['type'] ?? null
        ], $extra));

        $item->enchantments()->saveMany($this->enchantments);
        $item->attacks()->saveMany($this->attacks);

        return $item;
    }

    public function forHero(Hero $hero)
    {
        $clone = clone $this;
        $clone->hasItems = [
            'id' => $hero->id,
            'type' => Hero::RELATION_MORPH_MAP_KEY
        ];
        return $clone;
    }

    public function withEnchantments(Collection $enchantments = null)
    {
        if (! $enchantments) {
            $amount = rand(1, 4);
            $enchantments = Enchantment::query()->inRandomOrder()->take($amount)->get();
        }
        $clone = clone $this;
        $clone->enchantments = $enchantments;
        return $clone;
    }

    public function withAttacks(Collection $attacks = null)
    {
        $clone = clone $this;

        if (! $attacks) {

            if (! $clone->itemType) {
                /** @var ItemBase $itemBase */
                $itemBase = ItemBase::query()->whereHas('attacks')->inRandomOrder()->first();
                $clone->itemType = $itemBase->itemTypes()->inRandomOrder()->first();
            } else {
                $itemBase = $clone->itemType->itemBase;
            }
            $amount = rand(1, 3);
            $attacks = $itemBase->attacks()->inRandomOrder()->take($amount)->get();
        }

        $clone->attacks = $attacks;
        return $clone;
    }
}
