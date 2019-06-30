<?php

namespace Tests\Feature;

use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Slot;
use App\Domain\Models\SlotType;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroTest extends TestCase
{
    use DatabaseTransactions;

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

        /** @var \App\Domain\Models\Hero $hero */
        $hero = $squad->getHeroes()->first();

        $this->assertEquals($heroName, $hero->name);
        $this->assertEquals($heroClass, $hero->heroClass->name);
        $this->assertEquals($heroRace, $hero->heroRace->name);

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
