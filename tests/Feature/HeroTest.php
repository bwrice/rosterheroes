<?php

namespace Tests\Feature;

use App\Game;
use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\Measurable;
use App\MeasurableType;
use App\Player;
use App\GamePlayer;
use App\Slots\Slot;
use App\SlotType;
use App\Squad;
use App\User;
use App\Weeks\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroTest extends TestCase
{

    /**
     * @test
     */
    public function a_hero_can_be_created()
    {
        $this->withoutExceptionHandling();

        /** @var Squad $squad */
        $squad = factory(Squad::class)->states('starting-posts')->create();
        $user = Passport::actingAs($squad->user);

        $heroName = 'TestHero' . rand(1,999999);
        $heroRace = HeroRace::HUMAN;
        $heroClass = HeroClass::WARRIOR;

        $response = $this->json('POST','api/squad/' . $squad->uuid .  '/heroes', [
            'name' => $heroName,
            'race' => $heroRace,
            'class' => $heroClass
        ]);

        $squad = $squad->fresh();

        $response->assertStatus(201);
        $this->assertEquals(1, $squad->getHeroes()->count());

        /** @var Hero $hero */
        $hero = $squad->getHeroes()->first();

        $this->assertEquals($heroName, $hero->name);
        $this->assertEquals($heroClass, $hero->heroClass->name);
        $this->assertEquals($heroRace, $hero->getHeroRace()->name);

        $heroSlotTypes = SlotType::heroTypes();
        $heroSlots = $hero->slots;
        $this->assertEquals($heroSlotTypes->count(), $heroSlots->count());
        $heroSlotTypes->each(function(SlotType $slotType) use ($heroSlots) {
            $filtered = $heroSlots->filter(function(Slot $slot) use ($slotType) {
                return $slot->slot_type_id == $slotType->id;
            });

            $this->assertEquals(1, $filtered->count());
        });

        $measurableTypes = MeasurableType::heroTypes();
        $measurables = $hero->measurables;
        $this->assertEquals($measurableTypes->count(), $measurables->count());
        $measurableTypes->each(function(MeasurableType $measurableType) use ($measurables) {
            $filtered = $measurables->filter(function(Measurable $measurable) use ($measurableType) {
                return $measurable->measurable_type_id == $measurableType->id;
            });

            $this->assertEquals(1, $filtered->count());
        });

    }
}
