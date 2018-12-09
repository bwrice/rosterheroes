<?php

namespace Tests\Feature;

use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\Measurable;
use App\MeasurableType;
use App\Slot;
use App\SlotType;
use App\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_build_a_human_warrior()
    {
        $squad = factory(Squad::class)->create();

        $name = 'HumanWarrior_' . str_random(6);
        $race = HeroRace::HUMAN;
        $class = HeroClass::WARRIOR;

        $heroData = [
            'name' => $name,
            'race' => $race,
            'class' => $class,
        ];

        $hero = (new Hero())->build($squad, $heroData);

        $this->assertEquals($squad->id, $hero->squad_id, 'Squad ID matches');
        $this->assertEquals(HeroRace::where('name', '=', $race )->first()->id, $hero->hero_race_id, 'Hero Race matches');
        $this->assertEquals(HeroClass::where('name', '=', $class )->first()->id, $hero->hero_class_id, 'Hero Class Matches');

        $slotTypes = SlotType::heroTypes()->get();
        $slotTypes->each(function (SlotType $slotType) use ($hero) {
            $slotsOfSlotType = $hero->slots->filter( function (Slot $heroSlot) use ($slotType) {
                return $heroSlot->slot_type_id == $slotType->id;
            });
            $this->assertEquals(1, $slotsOfSlotType->count(), 'Correct amount of hero slots of slot type: ' . $slotType->name);
        } );

        $measurableTypes = MeasurableType::heroTypes()->get();
        $measurableTypes->each(function(MeasurableType $measurableType) use ($hero) {
            $measurablesOfType = $hero->measurables->filter(function(Measurable $heroMeasurable) use ($measurableType){
                return $heroMeasurable->measurable_type_id == $measurableType->id;
            });
            $this->assertEquals(1, $measurablesOfType->count(), 'Correct amount of hero measurables of measurable type: ' . $measurableType->name);
        });

        $startingBlueprints = $hero->getClassBehavior()->getStartItemBlueprints();

        $this->assertEquals($startingBlueprints->count(), $hero->getItems()->count());
    }
}
