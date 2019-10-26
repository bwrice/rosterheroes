<?php

namespace App\Domain\Models;

use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Behaviors\ItemBases\ItemBaseBehaviorInterface;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\Domain\Support\ItemNameBuilder;
use App\StorableEvents\ItemCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

/**
 * Class Item
 * @package App
 *
 * @property int $id
 * @property int $item_type_id
 * @property int $material_id
 * @property string $uuid
 * @property string $name
 *
 * @property ItemType $itemType
 * @property ItemClass $itemClass
 * @property ItemBlueprint $itemBlueprint
 * @property Material $material
 *
 * @property SlotCollection $slots
 * @property EnchantmentCollection $enchantments
 * @property AttackCollection $attacks
 */
class Item extends EventSourcedModel implements Slottable, HasAttacks
{
    const RELATION_MORPH_MAP = 'items';

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

    public function itemStorage()
    {
        return $this->morphTo();
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

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function getSlotTypeIDs(): array
    {
        return $this->getItemBaseBehavior()->getSlotTypeIDs();
    }

    public function getSlotsCount(): int
    {
        return $this->getItemBaseBehavior()->getSlotsCount();
    }

    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }

    public function getItemName(): string
    {
        return $this->name ?: $this->buildItemName();
    }

    public function getItemBaseBehavior(): ItemBaseBehaviorInterface
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

        if ($this->hasSlots) {
            return $this->hasSlots instanceof  UsesItems ? $this->hasSlots : null;
        }

        /** @var Slot $slot */
        $slot = $this->slots->first();
        if (! $slot) {
            return null;
        }

        return $slot->hero instanceof UsesItems ? $slot->hero : null;
    }

    public function getBurden(): int
    {
        $weight = $this->itemTypeGrade();
        $weight *= $this->itemType->getItemBaseBehavior()->getBurdenModifier();
        $weight *= $this->material->getBurdenModifier();
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

    /**
     * @param HasSlots|null $hasSlots
     * @return Item
     */
    public function setHasSlots(?HasSlots $hasSlots): Item
    {
        $this->hasSlots = $hasSlots;
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
}
