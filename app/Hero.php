<?php

namespace App;

use App\Events\HeroCreated;
use App\Events\HeroCreationRequested;
use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\NotEnoughSalaryException;
use App\Heroes\HeroCollection;
use App\Heroes\HeroPosts\HeroPost;
use App\Slots\Slotter;
use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slottable;
use App\Slots\SlottableCollection;
use App\Weeks\Week;
use App\Weeks\WeekCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Ramsey\Uuid\Uuid;

/**
 * Class Hero
 * @package App
 *
 * @property int $id
 * @property int $squad_id
 * @property int $hero_race_id
 * @property int $hero_class_id
 * @property int $hero_rank_id
 * @property int $game_player_id
 * @property int $salary
 * @property string $name
 *
 * @property HeroClass $heroClass
 * @property HeroPost $heroPost
 * @property GamePlayer $gamePlayer
 *
 * @property SlotCollection $slots
 * @property Collection $measurables
 */
class Hero extends EventSourcedModel implements HasSlots
{
    public function getRouteKeyName()
    {
        return 'uuid';
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

    public function getHeroRace()
    {
        return $this->heroPost->heroRace;
    }

    public function heroPost()
    {
        return $this->hasOne(HeroPost::class);
    }

    public function gamePlayer()
    {
        return $this->belongsTo(GamePlayer::class);
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
     * @return Slotter
     */
    protected function getEquipper()
    {
        return app()->make(Slotter::class);
    }

    /**
     * @param Slottable $slottable
     */
    public function equip(Slottable $slottable)
    {
        $this->getEquipper()->slot($this, $slottable);
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlotCollection
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
     * @param GamePlayer $gamePlayer
     */
    public function addGamePlayer(GamePlayer $gamePlayer)
    {
        if(! Week::isCurrent($gamePlayer->game->week)) {
            $exception = new InvalidWeekException();
            $exception->setWeeks($gamePlayer->game->week, new WeekCollection([
                Week::current()
            ]));
            throw $exception;
        }

        if (! $this->heroPost->getPositions()->intersect($gamePlayer->getPositions())->count() > 0 ) {
            $exception = new InvalidPositionsException();
            $exception->setPositions($this->heroPost->getPositions(), $gamePlayer->getPositions());
            throw $exception;
        }

        if(! $this->canAfford($gamePlayer->salary)) {
            $exception = new NotEnoughSalaryException();
            $exception->setSalaries($this->availableSalary(), $gamePlayer->salary);
            throw $exception;
        }

        if($gamePlayer->game->hasStarted()) {
            $exception = new GameStartedException();
            $exception->setGame($gamePlayer->game);
            throw $exception;
        }

        $this->game_player_id = $gamePlayer->id;
        $this->salary = $gamePlayer->salary;
        $this->save();
    }

    public function canAfford($salary)
    {
        return $this->availableSalary() >= (int) $salary;
    }
}
