<?php


namespace App\Factories\Models;


use App\Domain\Models\Enchantment;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemFactory
{
    /** @var int */
    protected $itemClassID;

    /** @var ItemType */
    protected $itemType;

    /** @var Material */
    protected $material;

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
    protected $maxItemTypeGrade;

    /** @var int */
    protected $minItemTypeGrade;

    /** @var int */
    protected $maxMaterialGrade;

    /** @var int */
    protected $minMaterialGrade;

    protected $lowestMaterialTypeGrade = false;

    protected $lowestItemTypeGrade = false;

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
        $material = $this->getMaterial($itemType);

        /** @var Item $item */
        $item = Item::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'item_class_id' => $this->itemClassID ?: ItemClass::generic()->id,
            'item_type_id' => $itemType->id,
            'material_id' => $material->id,
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

    public function maxItemTypeGrade(int $maxGrade)
    {
        $clone = clone $this;
        $clone->maxItemTypeGrade = $maxGrade;
        return $clone;
    }

    public function minItemTypeGrade(int $minGrade)
    {
        $clone = clone $this;
        $clone->minItemTypeGrade = $minGrade;
        return $clone;
    }

    public function maxMaterialGrade(int $maxGrade)
    {
        $clone = clone $this;
        $clone->maxMaterialGrade = $maxGrade;
        return $clone;
    }

    public function minMaterialGrade(int $minGrade)
    {
        $clone = clone $this;
        $clone->minMaterialGrade = $minGrade;
        return $clone;
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

    public function withLowestItemTypeGrade()
    {
        $clone = clone $this;
        $clone->lowestItemTypeGrade = true;
        return $clone;
    }

    public function withLowestMaterialTypeGrade()
    {
        $clone = clone $this;
        $clone->lowestMaterialTypeGrade = true;
        return $clone;
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

        if ($this->lowestItemTypeGrade) {
            return $query->orderBy('grade')->first();
        }

        if ($this->maxItemTypeGrade) {
            $query->where('grade', '<=', $this->maxItemTypeGrade);
        }

        if ($this->minItemTypeGrade) {
            $query->where('grade', '>=', $this->minItemTypeGrade);
        }
        return $query->inRandomOrder()->first();
    }

    /**
     * @param ItemType $itemType
     * @return Material
     */
    protected function getMaterial(ItemType $itemType)
    {
        if ($this->material) {
            return $this->material;
        }
        $query = $itemType->materials();
        if ($this->maxMaterialGrade) {
            $query = $query->where('grade', '<=', $this->maxMaterialGrade);
        }
        if ($this->minMaterialGrade) {
            $query = $query->where('grade', '>=', $this->minMaterialGrade);
        }
        /** @var Material $material */
        $material = $query->inRandomOrder()->first();
        return $material;
    }
}
