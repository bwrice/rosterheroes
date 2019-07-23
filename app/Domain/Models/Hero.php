<?php

namespace App\Domain\Models;

use App\Domain\Actions\AddSpiritToHeroAction;
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
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Actions\FillSlotAction;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Slot;
use App\Domain\Collections\SlotCollection;
use App\Domain\Interfaces\Slottable;
use App\Domain\Collections\SlottableCollection;
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
 * @property PlayerSpirit|null $playerSpirit
 *
 * @property SlotCollection $slots
 * @property Collection $measurables
 *
 * @method static HeroQueryBuilder query();
 */
class Hero extends EventSourcedModel implements HasSlots
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

    public static function uuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    {
        $items = collect();
        $this->slots()->with('items')->get()->each(function(Slot $slot) use (&$items) {
            $items->merge($slot->items);
        });

        return $items->unique();
    }

    public function getClassBehavior()
    {
        return $this->heroClass->getBehavior();
    }

    protected function addStartingGear()
    {
        $blueprints = $this->heroClass->getBehavior()->getStartItemBlueprints();
        $items = $blueprints->generate();
        $items->each(function (Item $item) {
            $this->equip($item);
        });
    }

    /**
     * @return FillSlotAction
     */
    protected function getEquipper()
    {
        return app()->make(FillSlotAction::class);
    }

    /**
     * @param \App\Domain\Interfaces\Slottable $slottable
     */
    public function equip(Slottable $slottable)
    {
        $this->getEquipper()->slot($this, $slottable);
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        return $this->slots->slotEmpty()->withSlotTypes($slotTypeIDs);
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->getSquad();
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlottableCollection
     */
    public function emptySlots(int $count = null, array $slotTypeIDs = []): SlottableCollection
    {
        return $this->slots->emptySlots($count, $slotTypeIDs);
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
    public function availableEssence()
    {
        // TODO: does this work?
        $heroEssence = $this->essence ?: 0;
        return $this->heroPost->squad->availableSpiritEssence() - $heroEssence;
    }

    /**
     * @param \App\Domain\Models\PlayerSpirit $playerSpirit
     * @return Hero
     */
    public function addPlayerSpirit(PlayerSpirit $playerSpirit)
    {
        $action = new AddSpiritToHeroAction($this, $playerSpirit);
        return $action(); //invoke and return
    }

    public function canAfford($essenceCost)
    {
        return $this->availableEssence() >= (int) $essenceCost;
    }
}
