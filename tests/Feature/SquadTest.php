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

        $response->assertStatus(201);

        /** @var Squad $squad */
        $squad = Squad::where('name', $name)->first();

        $this->assertEquals($user->squads->first()->id, $squad->id);
        $this->assertEquals(Squad::STARTING_SALARY, $squad->salary, "Squad has starting salary");
        $this->assertEquals(Squad::STARTING_GOLD, $squad->gold, "Squad has starting gold");
        $this->assertEquals(Squad::STARTING_FAVOR, $squad->favor, "Squad has starting favor");

        $this->assertEquals($squad->mobileStorageRank->getBehavior()->getSlotsCount(), $squad->slots()->count(), "Squad has it's slots");
        $this->assertEquals(count($heroesData), $squad->heroes->count(), "The Squad has Heroes");
    }
}
