<?php


namespace App\Factories\Models;


use App\Domain\Models\Enchantment;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
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

    protected $lowestItemTypeTier = false;

    protected $shopAvailable = false;

    protected $shopAcquiredAt = null;

    protected $shopAcquisitionCost = null;

    protected $hasItemRelations = null;

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
        $material = $this->getMaterial($itemType->itemBase);

        /** @var Item $item */
        $item = Item::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'item_class_id' => $this->itemClassID ?: ItemClass::generic()->id,
            'item_type_id' => $itemType->id,
            'material_id' => $material->id,
            'has_items_id' => $this->hasItems['id'] ?? null,
            'has_items_type' => $this->hasItems['type'] ?? null,
            'made_shop_available_at' => $this->shopAvailable ? Date::now() : null,
            'shop_acquired_at' => $this->shopAcquiredAt,
            'shop_acquisition_cost' => $this->shopAcquisitionCost
        ], $extra));

        if ($this->hasItemRelations) {
            $item->hasItems()->associate($this->hasItemRelations);
            $item->save();
            $item = $item->fresh();
        }

        $item->enchantments()->saveMany($this->enchantments);
        if ($this->withAttacks) {
            $attacks = $this->getAttacks($itemType);
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

    public function forSquad(Squad $squad)
    {
        $clone = clone $this;
        $clone->hasItems = [
            'id' => $squad->id,
            'type' => Squad::RELATION_MORPH_MAP_KEY
        ];
        return $clone;
    }

    public function forShop(Shop $shop, CarbonInterface $acquiredAt = null, $acquisitionCost = null)
    {
        $clone = clone ($this->forHasItems($shop));
        $clone->shopAcquisitionCost = ! is_null($acquisitionCost) ? $acquisitionCost : rand(10, 9999);
        $clone->shopAcquiredAt = $acquiredAt ?: Date::now();
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

    protected function getAttacks(ItemType $itemType)
    {
        if ($this->attacks) {
            return $this->attacks;
        }
        return $itemType->attacks()->inRandomOrder()->take($this->attacksAmount)->get();
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

    public function withLowestItemTypeTier()
    {
        $clone = clone $this;
        $clone->lowestItemTypeTier = true;
        return $clone;
    }

    public function withLowestMaterialTypeGrade()
    {
        $clone = clone $this;
        $clone->lowestMaterialTypeGrade = true;
        return $clone;
    }

    public function withItemType(ItemType $itemType)
    {
        $clone = clone $this;
        $clone->itemType = $itemType;
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

        if ($this->lowestItemTypeTier) {
            return $query->orderBy('tier')->first();
        }

        if ($this->maxItemTypeGrade) {
            $query->where('tier', '<=', $this->maxItemTypeGrade);
        }

        if ($this->minItemTypeGrade) {
            $query->where('tier', '>=', $this->minItemTypeGrade);
        }
        return $query->inRandomOrder()->first();
    }

    public function withMaterial(Material $material)
    {
        $clone = clone $this;
        $clone->material = $material;
        return $clone;
    }

    public function shopAvailable($available = true)
    {
        $clone = clone $this;
        $clone->shopAvailable = $available;
        return $clone;
    }

    public function forHasItems(Model $model)
    {
        $clone = clone $this;
        $clone->hasItemRelations = $model;
        return $clone;
    }

    /**
     * @param ItemBase $itemBase
     * @return Material
     */
    protected function getMaterial(ItemBase $itemBase)
    {
        if ($this->material) {
            return $this->material;
        }

        $query = Material::query()->whereHas('materialType', function (Builder $builder) use ($itemBase) {
            return $builder->whereIn('id', $itemBase->materialTypes()->pluck('id')->toArray());
        });

        if ($this->lowestMaterialTypeGrade) {
            /** @var Material $material */
            $material = $query->orderBy('grade')->first();
            return $material;
        }

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

    protected function beginnerItem(string $itemBaseName)
    {
        return $this->fromItemBases([$itemBaseName])->withLowestItemTypeTier()->withLowestMaterialTypeGrade();
    }

    public function beginnerShield()
    {
        return $this->beginnerItem(ItemBase::SHIELD);
    }

    public function beginnerSword()
    {
        return $this->beginnerItem(ItemBase::SWORD);
    }

    public function beginnerBow()
    {
        return $this->beginnerItem(ItemBase::BOW);
    }

    public function beginnerStaff()
    {
        return $this->beginnerItem(ItemBase::STAFF);
    }

    public function beginnerHeavyArmor()
    {
        return $this->beginnerItem(ItemBase::HEAVY_ARMOR);
    }

    public function beginnerLightArmor()
    {
        return $this->beginnerItem(ItemBase::LIGHT_ARMOR);
    }

    public function beginnerRobes()
    {
        return $this->beginnerItem(ItemBase::ROBES);
    }
}
