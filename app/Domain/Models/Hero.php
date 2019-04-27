<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasSlug;
use App\Events\HeroCreated;
use App\Events\HeroCreationRequested;
use App\Domain\Models\EventSourcedModel;
use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\NotEnoughSalaryException;
use App\Domain\Models\WeeklyGamePlayer;
use App\Domain\Models\HeroClass;
use App\Domain\Collections\HeroCollection;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Item;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Actions\FillSlot;
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
 * @property int $weekly_game_player_id
 * @property int $salary
 * @property string $name
 * @property string $slug
 *
 * @property HeroClass $heroClass
 * @property HeroPost $heroPost
 * @property HeroRace $heroRace
 * @property WeeklyGamePlayer|null $weeklyGamePlayer
 *
 * @property \App\Domain\Collections\SlotCollection $slots
 * @property Collection $measurables
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

//    public function getHeroRace()
//    {
//        return $this->heroPost->heroRace;
//    }

    public function heroPost()
    {
        return $this->hasOne(HeroPost::class);
    }

    public function weeklyGamePlayer()
    {
        return $this->belongsTo(WeeklyGamePlayer::class);
    }

    /**
     * @return Squad|null
     */
    public function getSquad()
    {
        return $this->heroPost ? $this->heroPost->squad : null;
    }

    public function addStartingSlots()
    {
        SlotType::heroTypes()->each(function (SlotType $slotType) {
            $this->slots()->create([
                'slot_type_id' => $slotType->id
            ]);
        });
    }

    public function addStartingMeasurables()
    {
        MeasurableType::heroTypes()->each(function (MeasurableType $measurableType) {
            Measurable::createWithAttributes([
                'has_measurables_type' => self::RELATION_MORPH_MAP_KEY,
                'has_measurables_id' => $this->id,
                'measurable_type_id' => $measurableType->id,
                'amount_raised' => 0
            ]);
        });
    }

    /**
     * @param array $attributes
     * @return Hero|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new HeroCreationRequested($attributes));

        return self::uuid($uuid);
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?Hero
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
     * @return FillSlot
     */
    protected function getEquipper()
    {
        return app()->make(FillSlot::class);
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
    public function availableSalary()
    {
        $heroSalary = $this->salary ?: 0;
        return $this->heroPost->squad->availableSalary() - $heroSalary;
    }

    /**
     * @param WeeklyGamePlayer $weeklyGamePlayer
     */
    public function addWeeklyGamePlayer(WeeklyGamePlayer $weeklyGamePlayer)
    {
        if(! Week::isCurrent($weeklyGamePlayer->week)) {
            $exception = new InvalidWeekException();
            $exception->setWeeks($weeklyGamePlayer->week, new WeekCollection([
                Week::current()
            ]));
            throw $exception;
        }

        if (! $this->heroRace->positions->intersect($weeklyGamePlayer->getPositions())->count() > 0 ) {
            $exception = new InvalidPositionsException();
            $exception->setPositions($this->heroRace->positions, $weeklyGamePlayer->getPositions());
            throw $exception;
        }

        if(! $this->canAfford($weeklyGamePlayer->salary)) {
            $exception = new NotEnoughSalaryException();
            $exception->setSalaries($this->availableSalary(), $weeklyGamePlayer->salary);
            throw $exception;
        }

        if($weeklyGamePlayer->gamePlayer->game->hasStarted()) {
            $exception = new GameStartedException();
            $exception->setGame($weeklyGamePlayer->gamePlayer->game);
            throw $exception;
        }

        $this->weekly_game_player_id = $weeklyGamePlayer->id;
        $this->save();
    }

    public function canAfford($salary)
    {
        return $this->availableSalary() >= (int) $salary;
    }
}
