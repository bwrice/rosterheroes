<?php


namespace App\Factories\Models;


use App\Domain\Models\Enchantment;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use Illuminate\Database\Eloquent\Builder;
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
        if (! $clone->itemClassID || $clone->itemClassID === ItemClass::generic()->id) {
            $clone->itemClassID = ItemClass::enchanted()->id;
        }
        $clone->enchantments = $enchantments;
        return $clone;
    }

    public function withAttacks(int $amount = 1, Collection $attacks = null)
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
            $attacks = $itemBase->attacks()->inRandomOrder()->take($amount)->get();
        }

        $clone->attacks = $attacks;
        return $clone;
    }

    public function fromItemBases(array $itemBaseNames)
    {
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) use ($itemBaseNames) {
            return $builder->whereIn('name', $itemBaseNames);
        })->inRandomOrder()->first();

        $clone = clone $this;
        $clone->itemType = $itemType;
        return $clone;
    }

    public function shield()
    {
        return $this->fromItemBases([
            ItemBase::SHIELD,
            ItemBase::PSIONIC_SHIELD,
        ]);
    }

    public function singleHandWeapon()
    {
        return $this->fromItemBases([
            ItemBase::DAGGER,
            ItemBase::SWORD,
            ItemBase::MACE,
            ItemBase::WAND,
            ItemBase::AXE,
            ItemBase::PSIONIC_ONE_HAND,
            ItemBase::THROWING_WEAPON,
        ]);
    }

    public function twoHandWeapon()
    {
        return $this->fromItemBases([
            ItemBase::CROSSBOW,
            ItemBase::TWO_HAND_SWORD,
            ItemBase::TWO_HAND_AXE,
            ItemBase::PSIONIC_TWO_HAND,
            ItemBase::BOW,
            ItemBase::STAFF,
            ItemBase::ORB,
            ItemBase::POLEARM,
        ]);
    }
}
