<?php

namespace App;

use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Slots\Slottable;
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
class Hero extends Model
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

    public function equip(Slottable $slottable)
    {
        $slotTypes = $slottable->getSlotTypes();
        $slotsCount = $slottable->getSlotsCount();

        $slotsOfType = $this->slots->withSlotTypes($slotTypes->pluck('id')->toArray());
        $emptySlots = $slotsOfType->slotEmpty();

        $diff = $emptySlots->count() - $slotsCount;

        if ( $diff >= 0 ) {

        } else {

            $slotsToEmpty = $slotsOfType->slotFilled()->take($diff);
            $slottables = $slotsToEmpty->getSlottables();
            $slottables = $slottables->removeFromSlots();

        }
    }
}
