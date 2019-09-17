<?php

namespace App\Domain\Models;

use App\Domain\Collections\AttackCollection;
use App\Domain\Collections\EnchantmentCollection;
use App\Domain\Behaviors\ItemBases\ItemBaseBehaviorInterface;
use App\Domain\Interfaces\HasAttacks;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\StorableEvents\ItemCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /**
     * @return BelongsToMany
     */
    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class)->withTimestamps();
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
        //TODO
        return 'Item';
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
        /** @var Slot $slot */
        $slot = $this->slots->first();
        if (! $slot) {
            return null;
        }
        return $slot->hasSlots instanceof UsesItems ? $slot->hasSlots : null;
    }

    public function getWeight(): int
    {
        $weight = $this->itemType->grade;
        $weight *= $this->itemType->getItemBaseBehavior()->getWeightModifier();
        $weight *= $this->material->getWeightModifier();
        return (int) ceil($weight);
    }

    public function getProtection(): int
    {
        $protection = $this->itemType->grade;
        $protection *= $this->itemType->getItemBaseBehavior()->getProtectionModifier();
        return (int) ceil($protection);
    }
}
