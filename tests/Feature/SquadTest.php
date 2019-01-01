<?php

namespace Tests\Feature;

use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\Measurable;
use App\MeasurableType;
use App\Slots\Slot;
use App\SlotType;
use App\Squad;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadTest extends TestCase
{
//    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_new_squad_can_be_created()
    {
        $this->withoutExceptionHandling();

        $heroesData = [
            [
                'name' => 'HeroOne-' . uniqid(),
                'class' => HeroClass::WARRIOR,
                'race' => HeroRace::DWARF
            ],
            [
                'name' => 'HeroTwo-' . uniqid(),
                'class' => HeroClass::WARRIOR,
                'race' => HeroRace::ORC,
            ],
            [
                'name' => 'HeroThree-' . uniqid(),
                'class' => HeroClass::RANGER,
                'race' => HeroRace::HUMAN
            ],
            [
                'name' => 'HeroFour-' . uniqid(),
                'class' => HeroClass::SORCERER,
                'race' => HeroRace::ELF
            ],
        ];

        /** @var User $user */
        $user = Passport::actingAs(factory(User::class)->create());

        $name = 'MyAwesomeSquad-' . uniqid();

        $response = $this->post('api/squad/create', [
           'name' => $name,
           'heroes' => $heroesData
        ]);

//        $response->assertStatus(201);

        /** @var Squad $squad */
        $squad = Squad::where('name', $name)->first();

        $this->assertEquals($user->squads->first()->id, $squad->id);
        $this->assertNotNull($squad->wagon,"The Squad has a wagon");
        $this->assertEquals($squad->wagon->squad_id, $squad->id, "The Wagon belongs to the Squad");
        $this->assertEquals(Squad::STARTING_SALARY, $squad->salary, "Squad has starting salary");
        $this->assertEquals(Squad::STARTING_GOLD, $squad->gold, "Squad has starting gold");
        $this->assertEquals(Squad::STARTING_FAVOR, $squad->favor, "Squad has starting favor");

        $wagonSlotsCount = $squad->wagon->slots()->get()->filter(function (Slot $slot) {
            return $slot->slotType->name == SlotType::UNIVERSAL;
        })->count();
        $this->assertEquals($squad->wagon->wagonSize->getBehavior()->getTotalSlotsCount(), $wagonSlotsCount, "Wagon has it's slots");

        $heroes = $squad->heroes;
        $this->assertEquals(count($heroesData), $heroes->count(), "The Squad has Heroes");

        $heroSlotTypes = SlotType::heroTypes()->get();
        $heroMeasurableTypes = MeasurableType::heroTypes()->get();

        $heroes->each(function (Hero $hero) use ($heroSlotTypes, $heroMeasurableTypes) {

            $heroSlotTypes->each(function (SlotType $slotType) use ($hero) {
               $slotsOfSlotType = $hero->slots->filter(function(Slot $heroSlot) use ($slotType){
                   return $heroSlot->slot_type_id == $slotType->id;
               });
               $this->assertEquals(1, $slotsOfSlotType->count(), 'Correct amount of hero slots of slot type: ' . $slotType->name);
            });

            $heroMeasurableTypes->each(function(MeasurableType $measurableType) use ($hero) {
               $measurablesOfType = $hero->measurables->filter(function(Measurable $heroMeasurable) use ($measurableType){
                   return $heroMeasurable->measurable_type_id == $measurableType->id;
               });
               $this->assertEquals(1, $measurablesOfType->count(), 'Correct amount of hero measurables of measurable type: ' . $measurableType->name);
            });
        });
    }
}
