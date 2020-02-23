<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\FillsGearSlots;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Support\Items\ItemNameBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use phpDocumentor\Reflection\Types\Static_;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 * @property int $item_type_id
 * @property int $material_id
 * @property string $uuid
 * @property string $name
 * @property int $damage_dealt
 * @property int $blocks
 *
 * @property string $has_items_type
 * @property string $has_items_id
 *
 * @property ItemType $itemType
 * @property ItemClass $itemClass
 * @property ItemBlueprint $itemBlueprint
 * @property Material $material
 * @property HasItems $hasItems
 *
 * @property EnchantmentCollection $enchantments
 * @property AttackCollection $attacks
 */
class Item extends EventSourcedModel implements HasAttacks, FillsGearSlots
{
    const RELATION_MORPH_MAP_KEY = 'items';

    protected $guarded = [];

    /** @var UsesItems|null */
    protected $usesItems;

    /** @var HasSlots|null */
    protected $hasSlots;

    /**
     * @return BelongsToMany
     */
    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class)->withTimestamps();
    }

    public function hasItems()
    {
        return $this->morphTo();
    }

    public function newCollection(array $models = [])
    {
        return new ItemCollection($models);
    }

    /**
     * @return BelongsToMany
     */
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

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function getSlotTypeIDs(): array
    {
        return $this->getItemBaseBehavior()->getSlotTypeIDs();
    }

    public function getSlotsCount(): int
    {
        return $this->getItemBaseBehavior()->getGearSlotsCount();
    }

    public function getItemName(): string
    {
        return $this->name ?: $this->buildItemName();
    }

    public function getItemBaseBehavior(): ItemBaseBehavior
    {
        return $this->itemType->getItemBaseBehavior();
    }

    protected function itemTypeGrade()
    {
        return $this->itemType->grade;
    }

    protected function buildItemName(): string
    {
        /** @var ItemNameBuilder $nameBuilder */
        $nameBuilder = app(ItemNameBuilder::class);
        return $nameBuilder->buildItemName($this);
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $gradeBonus = ($this->itemTypeGrade() ** .5)/25;
        $behaviorBonus = $this->getItemBaseBehavior()->getCombatSpeedBonus($this->getUsesItems());
        return $speed * (1 + $gradeBonus + $behaviorBonus);
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $gradeBonus = $this->itemTypeGrade()/100;
        $behaviorBonus = $this->getItemBaseBehavior()->getBaseDamageBonus($this->getUsesItems());
        return $baseDamage * (1 + $gradeBonus + $behaviorBonus);
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        $gradeBonus = $this->itemTypeGrade()/100;
        $behaviorBonus = $this->getItemBaseBehavior()->getDamageMultiplierBonus($this->getUsesItems());
        return $damageModifier * (1 + $gradeBonus + $behaviorBonus);
    }

    public function getUsesItems(): ?UsesItems
    {
        if ($this->usesItems) {
            return $this->usesItems;
        }

        return $this->hasItems instanceof  UsesItems ? $this->hasItems : null;
    }

    public function weight(): int
    {
        $weight = $this->itemTypeGrade()/10;
        $weight *= $this->itemType->getItemBaseBehavior()->getWeightModifier();
        $weight *= $this->material->getWeightModifier();
        return (int) ceil($weight);
    }

    public function getProtection(): int
    {
        $protection = $this->itemTypeGrade();
        $protection *= $this->itemType->getItemBaseBehavior()->getProtectionModifier();
        $protection *= $this->material->getProtectionModifier();
        return (int) ceil($protection);
    }

    public function getBlockChance(): float
    {
        $blockChance = 1 + ($this->itemTypeGrade()**.5)/5;
        $blockChance *= $this->itemType->getItemBaseBehavior()->getBlockChanceModifier();
        $usesItems = $this->getUsesItems();
        if ($usesItems) {
            $agilityAmount = $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
            $blockChance *= 1 + $agilityAmount**.5/50;
            $aptitudeAmount = $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
            $blockChance *= 1 + $aptitudeAmount**.5/50;
        }
        $blockChance *= $this->material->getBlockChanceModifier();
        return min(30, $blockChance);
    }

    public function getValue(): int
    {
        $value = $this->itemTypeGrade()**1.5;
        $value *= $this->material->getValueModifier();
        $value *= 1 + $this->enchantments->boostLevelSum()**.5/5;
        return (int) ceil($value);
    }

    /**
     * @param UsesItems|null $usesItems
     * @return Item
     */
    public function setUsesItems(?UsesItems $usesItems): Item
    {
        $this->usesItems = $usesItems;
        return $this;
    }

    public function setHasItems(?HasItems $hasItems)
    {
        $this->hasItems = $hasItems;
        return $this;
    }

    public function adjustResourceCostAmount(float $amount): float
    {
        return $amount * $this->getItemBaseBehavior()->getResourceCostAmountModifier();
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return $amount * $this->getItemBaseBehavior()->getResourceCostPercentModifier();
    }

    public function getValidGearSlotTypes(): array
    {
        return $this->getItemBaseBehavior()->getValidGearSlotTypes();
    }

    public function getGearSlotsNeededCount(): int
    {
        return $this->getItemBaseBehavior()->getGearSlotsCount();
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function attachToHasItems(HasItems $hasItems)
    {
        $this->has_items_type = $hasItems->getMorphType();
        $this->has_items_id = $hasItems->getMorphID();
        $this->save();
        return $this;
    }

    public function clearHasItems()
    {
        $this->has_items_type = null;
        $this->has_items_id = null;
        $this->save();
        return $this;
    }

    public function belongsToHasItems(HasItems $hasItems)
    {
        if (is_null($this->hasItems)) {
            return false;
        }
        return $this->has_items_type === $hasItems->getMorphType()
            && $this->has_items_id === $hasItems->getMorphID();
    }
}
