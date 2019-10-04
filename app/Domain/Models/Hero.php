<?php

namespace App\Domain\Models;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Actions\RemoveSpiritFromHeroAction;
use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\MeasurableCollection;
use App\Domain\Interfaces\HasMeasurables;
use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Item;
use App\Domain\QueryBuilders\HeroQueryBuilder;
use App\Domain\Traits\HasSlug;
use App\StorableEvents\HeroCreated;
use App\Domain\Models\EventSourcedModel;
use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\NotEnoughEssenceException;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\HeroClass;
use App\Domain\Collections\HeroCollection;
use App\Domain\Actions\FillSlotsWithItemAction;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Domain\Collections\WeekCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Ramsey\Uuid\Uuid;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Hero
 * @package App
 *
 * @property int $id
 * @property int $squad_id
 * @property int $hero_race_id
 * @property int $hero_class_id
 * @property int $hero_rank_id
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
 *
 * @method static HeroQueryBuilder query();
 */
class Hero extends EventSourcedModel implements HasSlots, HasMeasurables, UsesItems
{
    use HasSlug;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    const RELATION_MORPH_MAP_KEY = 'heroes';

    protected $guarded = [];

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
        return $this->morphMany(Measurable::class, 'has_measurables');
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

    public function getClassBehavior()
    {
        return $this->heroClass->getBehavior();
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

    public function costToRaiseMeasurable(Measurable $measurable, int $amount = 1): int
    {
        return $this->heroClass->getBehavior()->costToRaiseMeasurable($measurable, $amount);
    }

    public function spentOnRaisingMeasurable(Measurable $measurable): int
    {
        return $this->heroClass->getBehavior()->spentOnRaisingMeasurable($measurable);
    }

    public function getCurrentMeasurableAmount(Measurable $measurable): int
    {
        return $this->heroClass->getBehavior()->getCurrentMeasurableAmount($measurable);
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

    public function getMeasurableAmount(string $measurableTypeName): int
    {
        $measurable = $this->getMeasurable($measurableTypeName);
        return $this->getCurrentMeasurableAmount($measurable);
    }

    public function getUniqueIdentifier(): string
    {
        return $this->uuid;
    }
}
