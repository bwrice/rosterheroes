<?php

namespace Tests\Unit;

use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\Item;
use App\Measurable;
use App\MeasurableType;
use App\Slots\Slot;
use App\SlotType;
use App\Squad;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroTest extends TestCase
{
//    /**
//     * @test
//     */
//    public function it_can_equip_an_item()
//    {
//        //Move this to an integration test, SlotterTest will cover the unit functionality
//        /** @var Item $item */
//        $item = factory(Item::class)->create();
//        /** @var Hero $hero */
//        $hero = factory(Hero::class)->create();
//
//        $this->assertGreaterThan(0, $hero->slots()->count(), 'Hero has slots');
//
//        $hero->equip($item);
//
//        $hero->hasEquipped($item);
//    }

    /**
     * @test
     * @dataProvider provides_a_hero_can_be_generated
     */
    public function a_hero_can_be_generated($name, $className, $raceName)
    {
        $squad = factory(Squad::class)->create();

        /** @var Hero $hero */
        $hero = Hero::generate($squad, $name, $className, $raceName );

        $this->assertEquals($className, $hero->heroClass->name);
        $this->assertEquals($raceName, $hero->heroRace->name);

        $measurables = $hero->measurables;
        $measurableTypes = MeasurableType::heroTypes()->get();
        $this->assertEquals($measurableTypes->count(), $measurables->count(), "Hero has the correct amount of measurables");
        $measurableTypes->each(function(MeasurableType $type) use ($measurables) {
            $measurablesOfType = $measurables->filter(function(Measurable $measurable) use($type) {
                return $measurable->measurable_type_id == $type->id;
            });
            $this->assertEquals(1, $measurablesOfType->count(), "Hero has 1 measurable of each type");
        });

        $slots = $hero->slots;
        $heroSlotTypes = SlotType::heroTypes()->get();
        $this->assertEquals($heroSlotTypes->count(), $slots->count(), "Hero has the correct amount of slots");
        $heroSlotTypes->each(function(SlotType $slotType) use ($slots) {
           $slotsOfType = $slots->filter(function(Slot $slot) use ($slotType) {
               return $slot->slot_type_id == $slotType->id;
           });
           $this->assertEquals(1, $slotsOfType->count(), "Hero has only 1 slot of each hero type");
        });
    }

    public function provides_a_hero_can_be_generated()
    {
        return [
            'dwarf warrior' => [
                'name' => 'TestWarrior' . uniqid(),
                'className' => HeroClass::WARRIOR,
                'raceName' => HeroRace::DWARF
            ],
            'elf sorcerer' => [
                'name' => 'TestWarrior' . uniqid(),
                'className' => HeroClass::SORCERER,
                'raceName' => HeroRace::ELF
            ],
            'human ranger' => [
                'name' => 'TestWarrior' . uniqid(),
                'className' => HeroClass::RANGER,
                'raceName' => HeroRace::HUMAN
            ],
            'orc warrior' => [
                'name' => 'TestWarrior' . uniqid(),
                'className' => HeroClass::WARRIOR,
                'raceName' => HeroRace::ORC
            ]
        ];
    }
}
