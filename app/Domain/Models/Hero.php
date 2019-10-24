<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\HeroClasses\HeroClassBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\MeasurableCollection;
use App\Domain\Collections\SpellCollection;
use App\Domain\Interfaces\SpellCaster;
use App\Domain\Interfaces\UsesItems;
use App\Domain\QueryBuilders\HeroQueryBuilder;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Collections\HeroCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Collections\SlotCollection;

/**
 * Class Hero
 * @package App
 *
 * @property int $id
 * @property int $squad_id
 * @property int $hero_race_id
 * @property int $hero_class_id
 * @property int $hero_rank_id
 * @property int $combat_position_id
 * @property int $player_spirit_id
 * @property int $essence
 * @property string $name
 * @property string $slug
 *
 * @property HeroClass $heroClass
 * @property HeroPost $heroPost
 * @property HeroRace $heroRace
 * @property CombatPosition $combatPosition
 * @property PlayerSpirit|null $playerSpirit
 *
 * @property SlotCollection $slots
 * @property SlotCollection $slotsThatHaveItems
 * @property MeasurableCollection $measurables
 * @property SpellCollection $spells
 *
 * @method static HeroQueryBuilder query();
 */
class Hero extends EventSourcedModel implements HasSlots, UsesItems, SpellCaster
{
    use HasNameSlug;

    const RELATION_MORPH_MAP_KEY = 'heroes';

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function heroResourceRelations()
    {
        return [
            'heroRace',
            'heroClass',
            'combatPosition',
            'playerSpirit.player',
            'playerSpirit.game.homeTeam',
            'playerSpirit.game.awayTeam',
            'measurables.measurableType',
            'slots.slotType',
            'slots.hasSlots',
            'slots.item.itemType.itemBase',
            'slots.item.material.materialType',
            'slots.item.itemClass',
            'slots.item.attacks.attackerPosition',
            'slots.item.attacks.targetPosition',
            'slots.item.attacks.targetPriority',
            'slots.item.attacks.damageType',
            'slots.item.enchantments.measurableBoosts.measurableType',
            'slots.item.enchantments.measurableBoosts.booster',
            'spells.measurableBoosts.measurableType',
            'spells.measurableBoosts.booster'
        ];
    }

    public function newCollection(array $models = [])
    {
        return new HeroCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new HeroQueryBuilder($query);
    }

    public function slots()
    {
        return $this->morphMany(Slot::class, 'has_slots');
    }

    public function measurables()
    {
        return $this->hasMany(Measurable::class);
    }

    public function heroClass()
    {
        return $this->belongsTo(HeroClass::class);
    }

    public function heroRace()
    {
        return $this->belongsTo(HeroRace::class);
    }

    public function heroPost()
    {
        return $this->hasOne(HeroPost::class);
    }

    public function combatPosition()
    {
        return $this->belongsTo(CombatPosition::class);
    }

    public function playerSpirit()
    {
        return $this->belongsTo(PlayerSpirit::class);
    }

    public function spells()
    {
        return $this->belongsToMany(Spell::class)->withTimestamps();
    }

    /**
     * @return Squad|null
     */
    public function getSquad()
    {
        return $this->heroPost ? $this->heroPost->squad : null;
    }

    /**
     * @return ItemCollection
     */
    public function getItems(): ItemCollection
    {
        $items = new ItemCollection();
        $this->slots->loadMissing('item')->each(function (Slot $slot) use ($items) {
            if ($slot->item) {
                $items->push($slot->item);
            }
        });

        return $items->unique();
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        return $this->slots->slotEmpty()->withSlotTypes($slotTypeIDs)->take($count);
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->getSquad();
    }

    /**
     * @param array $with
     * @return HasSlots
     *
     * Wraps Eloquent fresh() method to force HasSlots return type
     */
    public function getFresh($with = []): HasSlots
    {
        return $this->fresh($with);
    }

    /**
     * @return SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }

    /**
     * @return int
     */
    public function essenceUsed()
    {
        if ($this->playerSpirit) {
            return $this->playerSpirit->essence_cost;
        }
        return 0;
    }

    /**
     * @return int
     */
    public function availableEssence()
    {
        /*
         * Add current hero essence used back,
         * because availableSpiritEssence() on squad is subtracting it out
         */
        return $this->heroPost->squad->availableSpiritEssence() + $this->essenceUsed();
    }

    public function costToRaiseMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $amountAlreadyRaised, int $amountToRaise = 1): int
    {
        return $this->getHeroClassBehavior()->costToRaiseMeasurable($measurableTypeBehavior, $amountAlreadyRaised, $amountToRaise);
    }

    public function spentOnRaisingMeasurable(MeasurableTypeBehavior $measurableTypeBehavior, int $amountRaised): int
    {
        return $this->getHeroClassBehavior()->spentOnRaisingMeasurable($measurableTypeBehavior, $amountRaised);
    }

    public function getBuffsSumAmountForMeasurable(MeasurableTypeBehavior $measurableTypeBehavior): int
    {
        $enchantsBonus = $this->getEnchantments()->getBoostAmount($measurableTypeBehavior->getTypeName());
        return $enchantsBonus;
    }

    public function getEnchantments()
    {
        return $this->getSlots()->getItems()->getEnchantments();
    }

    /**
     * @param string $measurableTypeName
     * @return Measurable
     */
    public function getMeasurable(string $measurableTypeName)
    {
        $measurable = $this->loadMissing('measurables.measurableType')->measurables->first(function (Measurable $measurable) use ($measurableTypeName) {
            return $measurable->measurableType->name === $measurableTypeName;
        });

        if (!$measurable) {
            throw new \RuntimeException('Hero: ' . $this->name . ' does not have a measurable of type: ' . $measurableTypeName);
        }
        return $measurable;
    }

    public function availableExperience(): int
    {
        $squad = $this->getSquad();
        if (!$squad) {
            return 0;
        }

        $squadExp = $squad->experience;
        $expSpentOnMeasurables = $this->measurables->experienceSpentOnRaising();
        return $squadExp - $expSpentOnMeasurables;
    }

    public function getBuffedMeasurableAmount(string $measurableTypeName): int
    {
        return $this->getMeasurable($measurableTypeName)->getPreBuffedAmount();
    }

    public function getUniqueIdentifier(): string
    {
        return $this->uuid;
    }

    public function getMeasurableStartingAmount(MeasurableTypeBehavior $measurableTypeBehavior): int
    {
        return $this->getHeroClassBehavior()->getMeasurableStartingAmount($measurableTypeBehavior);
    }

    /**
     * @return HeroClassBehavior
     */
    protected function getHeroClassBehavior(): HeroClassBehavior
    {
        return $this->heroClass->getBehavior();
    }

    public function getSpellPower(): float
    {
        $focus = $this->getMeasurable(MeasurableType::FOCUS)->getPreBuffedAmount();
        $aptitude = $this->getMeasurable(MeasurableType::APTITUDE)->getPreBuffedAmount();
        return  round((($focus + $aptitude) / 20), 2);
    }

    public function getAmountUsedForMeasurable(MeasurableTypeBehavior $measurableTypeBehavior): int
    {
        if ($measurableTypeBehavior->getTypeName() === MeasurableType::MANA) {
            return $this->getManaUsed();
        }
        return 0;
    }

    public function getManaUsed()
    {
        return $this->spells->manaCost();
    }

    public function getAvailableMana(): int
    {
        return $this->getMeasurable(MeasurableType::MANA)->getCurrentAmount();
    }

    public function getSpellBoostMultiplier(): float
    {
        return 1 + $this->getSpellPower()/10;
    }
}
