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
 * @property string $uuid
 * @property string $name
 *
 * @property ItemType $itemType
 * @property ItemClass $itemClass
 * @property ItemBlueprint $itemBlueprint
 * @property MaterialType $materialType
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

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class);
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
        $gradeModifier = 1 + ($this->itemTypeGrade() ** .5)/10;
        $behaviorModifier = $this->getItemBaseBehavior()->getCombatSpeedModifier($this->getUsesItems());
        return $speed * $gradeModifier * $behaviorModifier;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        $gradeModifier = 1 + ($this->itemTypeGrade() ** .5)/5;
        $behaviorModifier = $this->getItemBaseBehavior()->getBaseDamageModifier($this->getUsesItems());
        return $baseDamage * $gradeModifier * $behaviorModifier;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        $gradeModifier = 1 + ($this->itemTypeGrade() ** .5)/5;
        $behaviorModifier = $this->getItemBaseBehavior()->getDamageMultiplierModifier($this->getUsesItems());
        return $damageModifier * $gradeModifier * $behaviorModifier;
    }

    public function getUsesItems(): ?UsesItems
    {
        return null;
    }

//    protected function getHasItems()
//    {
//        $slot = $this->slots->first();
//        if ($slot) {
//
//        }
//    }
}
