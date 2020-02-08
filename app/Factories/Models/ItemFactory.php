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

    /** @var Collection|null */
    protected $attacks;

    /** @var bool */
    protected $withAttacks;

    /** @var int */
    protected $attacksAmount = 1;

    /** @var array|null */
    protected $itemBaseNames;

    /** @var int */
    protected $maxGrade;

    /** @var int */
    protected $minGrade;

    public function __construct()
    {
        $this->enchantments = collect();
    }

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $itemType = $this->getItemType();

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
        if ($this->withAttacks) {
            $attacks = $this->getAttacks($itemType->itemBase);
            $item->attacks()->saveMany($attacks);
        }

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
        $clone->withAttacks = true;
        $clone->attacksAmount = $amount;
        $clone->attacks = $attacks;
        return $clone;
    }

    protected function getAttacks(ItemBase $itemBase)
    {
        if ($this->attacks) {
            return $this->attacks;
        }
        return $itemBase->attacks()->inRandomOrder()->take($this->attacksAmount)->get();
    }

    public function fromItemBases(array $itemBaseNames)
    {
        $clone = clone $this;
        $clone->itemBaseNames = $itemBaseNames;
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

    /**
     * @return ItemType
     */
    protected function getItemType()
    {
        if ($this->itemType) {
            return $this->itemType;
        }
        $query = ItemType::query();
        if ($this->itemBaseNames) {
            $query = $query->whereHas('itemBase', function (Builder $builder) {
                return $builder->whereIn('name', $this->itemBaseNames);
            });
        }
        if ($this->maxGrade) {
            $query->where('grade', '<=', $this->maxGrade);
        }

        if ($this->minGrade) {
            $query->where('grade', '>=', $this->minGrade);
        }
        return $query->inRandomOrder()->first();
    }
}
