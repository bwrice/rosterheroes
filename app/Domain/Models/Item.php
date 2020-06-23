<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Interfaces\FillsGearSlots;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\Morphable;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Support\Items\ItemNameBuilder;
use Carbon\CarbonInterface;
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
 * @property int $minion_damage_dealt
 * @property int $titan_damage_dealt
 * @property int $side_quest_damage_dealt
 * @property int $quest_damage_dealt
 * @property int $minion_kills
 * @property int $titan_kills
 * @property int $combat_kills
 * @property int $side_quest_kills
 * @property int $quest_kills
 * @property int $attacks_blocked
 * @property int|null $item_blueprint_id
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $made_shop_available_at
 * @property CarbonInterface $shop_acquired_at
 * @property int|null $shop_acquisition_cost
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

    protected $dates = [
        'updated_at',
        'created_at',
        'made_shop_available_at',
        'shop_acquired_at'
    ];

    protected $transaction = [
        'to' => null,
        'from' => null
    ];

    public static function resourceRelations()
    {
        return [
            'itemType.itemBase',
            'itemType.attacks.attackerPosition',
            'itemType.attacks.targetPosition',
            'itemType.attacks.targetPriority',
            'itemType.attacks.damageType',
            'material.materialType',
            'itemClass',
            'attacks',
            'enchantments.measurableBoosts.measurableType',
            'enchantments.measurableBoosts.booster',
        ];
    }

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

    protected function itemTypeTier()
    {
        return $this->itemType->tier;
    }

    protected function buildItemName(): string
    {
        /** @var ItemNameBuilder $nameBuilder */
        $nameBuilder = app(ItemNameBuilder::class);
        return $nameBuilder->buildItemName($this);
    }

    /**
     * @return AttackCollection
     */
    public function getAttacks()
    {
        return $this->itemType->attacks->merge($this->attacks);
    }

    public function adjustCombatSpeed(float $speed): float
    {
        $tierBonus = ($this->itemTypeTier() ** .5)/2;
        $materialBonus = $this->material->getSpeedModifierBonus();
        $combatSpeed = $speed * (1 + $tierBonus + $materialBonus);
        $combatSpeed = $this->getItemBaseBehavior()->adjustCombatSpeed($combatSpeed, $this->getUsesItems());
        return $combatSpeed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $gradeBonus = $this->itemTypeTier()/10;
        $materialBonus = $this->material->getBaseDamageModifierBonus();
        $baseDamage = $baseDamage * (1 + $gradeBonus + $materialBonus);
        $baseDamage = $this->getItemBaseBehavior()->adjustBaseDamage($baseDamage, $this->getUsesItems());
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        $gradeBonus = $this->itemTypeTier()/10;
        $materialBonus = $this->material->getDamageMultiplierModifierBonus();
        $damageMultiplier = $damageMultiplier * (1 + $gradeBonus + $materialBonus);
        $damageMultiplier = $this->getItemBaseBehavior()->adjustDamageMultiplier($damageMultiplier, $this->getUsesItems());
        return $damageMultiplier;
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
        $weight = $this->itemTypeTier();
        $weight *= $this->itemType->getItemBaseBehavior()->getWeightModifier();
        $weight *= $this->material->getWeightModifier();
        return (int) ceil($weight);
    }

    public function getProtection(): int
    {
        $protection = 10 * $this->itemTypeTier();
        $protection *= $this->itemType->getItemBaseBehavior()->getProtectionModifier();
        $protection *= $this->material->getProtectionModifier();
        return (int) ceil($protection);
    }

    public function getBlockChance(): float
    {
        $blockChance = $this->itemTypeTier();
        $blockChance *= $this->itemType->getItemBaseBehavior()->getBlockChanceModifier();
        $usesItems = $this->getUsesItems();
        if ($usesItems) {
            $agilityAmount = $usesItems->getBuffedMeasurableAmount(MeasurableType::AGILITY);
            $blockChance *= 1 + $agilityAmount**.5/50;
            $aptitudeAmount = $usesItems->getBuffedMeasurableAmount(MeasurableType::APTITUDE);
            $blockChance *= 1 + $aptitudeAmount**.5/50;
        }
        $blockChance *= $this->material->getBlockChanceModifier();
        return min(60, $blockChance);
    }

    public function getValue(): int
    {
        $value = (5 * $this->itemTypeTier())**1.5;
        $value *= $this->material->getValueModifier();
        $value *= 1 + $this->enchantments->boostLevelSum()**.5/8;
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

    public function adjustResourceCostAmount(float $amount): int
    {
        return (int) floor($amount * $this->getItemBaseBehavior()->getResourceCostAmountModifier());
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
        if ($this->hasItems) {
            $this->setTransactionFrom($this->hasItems);
        }
        $this->has_items_type = $hasItems->getMorphType();
        $this->has_items_id = $hasItems->getMorphID();
        $this->save();
        $this->setTransactionTo($hasItems);
        return $this;
    }

    public function clearHasItems()
    {
        $this->has_items_type = null;
        $this->has_items_id = null;
        $this->save();
        return $this;
    }

    public function ownedByMorphable(Morphable $hasItems)
    {
        if (is_null($this->hasItems)) {
            return false;
        }
        return $this->has_items_type === $hasItems->getMorphType()
            && $this->has_items_id === $hasItems->getMorphID();
    }

    public function getResourceCosts(int $attackTier, DamageTypeBehavior $damageTypeBehavior, ?int $targetsCount): ResourceCostsCollection
    {
        if ($attackTier === 1 && $targetsCount === 1) {
            return new ResourceCostsCollection();
        }

        $costMagnitude = $damageTypeBehavior->getResourceCostMagnitude($attackTier, $targetsCount);
        return $this->getItemBaseBehavior()->getResourceCosts($attackTier, $costMagnitude);
    }

    /**
     * @return array
     */
    public function getTransaction(): array
    {
        return $this->transaction;
    }

    public function setTransactionTo(HasItems $hasItems)
    {
        return $this->transaction['to'] = $hasItems->getTransactionIdentification();
    }

    public function setTransactionFrom(HasItems $hasItems)
    {
        return $this->transaction['from'] = $hasItems->getTransactionIdentification();
    }
}
