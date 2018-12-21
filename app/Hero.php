<?php

namespace App;

use App\Slots\Equipper;
use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slottable;
use App\Slots\SlottableCollection;
use function foo\func;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Hero
 * @package App
 *
 * @property int $id
 * @property int $squad_id
 * @property int $hero_race_id
 * @property int $hero_class_id
 * @property int $hero_rank_id
 * @property string $name
 *
 * @property HeroClass $heroClass
 * @property Squad $squad
 *
 * @property SlotCollection $slots
 * @property Collection $measurables
 */
class Hero extends Model implements HasSlots
{

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

    protected function getWagon()
    {
        return $this->squad->wagon;
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

    public function build(Squad $squad, $heroData)
    {
        $this->squad_id = $squad->id;
        $this->hero_race_id = HeroRace::where('name', '=', $heroData['race'])->first()->id;
        $this->hero_class_id = HeroClass::where('name', '=', $heroData['class'])->first()->id;
        $this->hero_rank_id = HeroRank::getStarting()->id;
        $this->name = $heroData['name'];
        $this->save();
        $this->buildSlots();
        $this->buildMeasurables();
        return $this->fresh();
    }

    protected function buildSlots()
    {
        $slotTypes = SlotType::heroTypes()->get();

        $slotTypes->each(function(SlotType $slotType) {
            $this->slots()->create([
                'slot_type_id' => $slotType->id,
            ]);
        });
    }

    protected function buildMeasurables()
    {
        $measurableTypes = MeasurableType::heroTypes()->get();

        $measurableTypes->each(function(MeasurableType $measurableType) {
            $this->measurables()->create([
                'measurable_type_id' => $measurableType->id,
                'amount_raised' => 0
            ]);
        });
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
     * @return Equipper
     */
    protected function getEquipper()
    {
        return app()->make(Equipper::class);
    }

    /**
     * @param Slottable $slottable
     */
    protected function equip(Slottable $slottable)
    {
        $this->getEquipper()->equip($this, $slottable);
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        return $this->slots->slotEmpty();
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->getWagon();
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlottableCollection
     */
    public function emptySlots(int $count, array $slotTypeIDs = []): SlottableCollection
    {
        //TODO: Next step is to move this all to SlotCollection
        $slottables = new SlottableCollection();
        $this->slots->filter(function(Slot $slot) use ($slotTypeIDs) {
            return in_array( $slot->slot_type_id, $slotTypeIDs );
        })->loadMissing('slottable')->filter(function (Slot $slot) {
            return $slot->slottable !== null;
        })->take($count)->each(function (Slot $slot) use ($slottables) {
            $slottables->push($slot->slottable);
        });

        return $slottables;
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
}
