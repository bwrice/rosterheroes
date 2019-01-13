<?php

namespace App;

use App\Events\HeroCreated;
use App\Events\HeroCreationRequested;
use App\Slots\Slotter;
use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slottable;
use App\Slots\SlottableCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
 * @property int $player_week_id
 * @property int $salary
 * @property string $name
 *
 * @property HeroClass $heroClass
 * @property HeroRace $heroRace
 * @property Squad $squad
 * @property PlayerWeek $playerWeek
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

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function playerWeek()
    {
        return $this->belongsTo(PlayerWeek::class);
    }


    public static function generate(Squad $squad, $name, $heroClass, $heroRace)
    {
        $hero = self::createWithAttributes([
            'squad_id' => $squad->id,
            'name' => $name,
            'hero_class_id' => HeroClass::where('name', '=', $heroClass)->first()->id,
            'hero_race_id' => HeroRace::where('name', '=', $heroRace)->first()->id,
            'hero_rank_id' => HeroRank::getStarting()->id
        ]);

        // Hooked into for adding slots, measurables...
        event(new HeroCreated($hero));

        return $hero;
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

//    public function build(Squad $squad, $heroData)
//    {
//        $this->squad_id = $squad->id;
//        $this->hero_race_id = HeroRace::where('name', '=', $heroData['race'])->first()->id;
//        $this->hero_class_id = HeroClass::where('name', '=', $heroData['class'])->first()->id;
//        $this->hero_rank_id = HeroRank::getStarting()->id;
//        $this->name = $heroData['name'];
//        $this->save();
//        $this->buildSlots();
//        $this->buildMeasurables();
//        return $this->fresh();
//    }
//
//    protected function buildSlots()
//    {
//        $slotTypes = SlotType::heroTypes()->get();
//
//        $slotTypes->each(function(SlotType $slotType) {
//            $this->slots()->create([
//                'slot_type_id' => $slotType->id,
//            ]);
//        });
//    }
//
//    protected function buildMeasurables()
//    {
//        $measurableTypes = MeasurableType::heroTypes()->get();
//
//        $measurableTypes->each(function(MeasurableType $measurableType) {
//            $this->measurables()->create([
//                'measurable_type_id' => $measurableType->id,
//                'amount_raised' => 0
//            ]);
//        });
//    }

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
        return $this->squad;
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

    public function addPlayerWeek(PlayerWeek $playerWeek)
    {
        $this->player_week_id = $playerWeek->id;
        $this->salary = $playerWeek->salary;
        $this->save();
    }
}
