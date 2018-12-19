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
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_new_squad_can_be_created()
    {
        $this->withoutExceptionHandling();

        $heroesData = [
            [
                'name' => 'HeroOne',
                'class' => HeroClass::WARRIOR,
                'race' => HeroRace::DWARF
            ],
            [
                'name' => 'HeroTwo',
                'class' => HeroClass::WARRIOR,
                'race' => HeroRace::ORC,
            ],
            [
                'name' => 'HeroThree',
                'class' => HeroClass::RANGER,
                'race' => HeroRace::HUMAN
            ],
            [
                'name' => 'HeroFour',
                'class' => HeroClass::SORCERER,
                'race' => HeroRace::ELF
            ],
        ];

        /** @var User $user */
        $user = Passport::actingAs(factory(User::class)->create());

        $name = 'MyAwesomeSquad';

        $response = $this->post('api/squad/create', [
           'name' => $name,
           'heroes' => $heroesData
        ]);

        $response->assertStatus(201);

        /** @var Squad $squad */
        $squad = Squad::where('name', $name)->first();

        $this->assertEquals($user->squads->first()->id, $squad->id);
        $this->assertEquals($squad->wagon->squad_id, $squad->id);
        $this->assertEquals($squad->wagon->wagonSize->getBehavior()->getTotalSlotsCount(), $squad->wagon->slots()->count());

        $heroes = $squad->heroes;
        $this->assertEquals(count($heroesData), $heroes->count());
        $heroes->each(function(Hero $hero) {

            $slotTypes = SlotType::heroTypes()->get();
            $slotTypes->each(function(SlotType $slotType) use ($hero) {
               $slotsOfSlotType = $hero->slots->filter(function(Slot $heroSlot) use ($slotType){
                   return $heroSlot->slot_type_id == $slotType->id;
               });
               $this->assertEquals(1, $slotsOfSlotType->count(), 'Correct amount of hero slots of slot type: ' . $slotType->name);
            });

            $measurableTypes = MeasurableType::heroTypes()->get();
            $measurableTypes->each(function(MeasurableType $measurableType) use ($hero) {
               $measurablesOfType = $hero->measurables->filter(function(Measurable $heroMeasurable) use ($measurableType){
                   return $heroMeasurable->measurable_type_id == $measurableType->id;
               });
               $this->assertEquals(1, $measurablesOfType->count(), 'Correct amount of hero measurables of measurable type: ' . $measurableType->name);
            });
        });
    }
}
