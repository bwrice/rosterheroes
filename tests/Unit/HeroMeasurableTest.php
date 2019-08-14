<?php

namespace Tests\Unit;

use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroMeasurableTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $warrior;

    /** @var Hero */
    protected $ranger;

    /** @var Hero */
    protected $sorcerer;

    public function setUp(): void
    {
        parent::setUp();

        $warriorClass = HeroClass::warrior();
        $this->warrior = factory(Hero::class)->state('with-measurables')->create([
            'hero_class_id' => $warriorClass->id
        ]);

        $rangerClass = HeroClass::ranger();
        $this->ranger = factory(Hero::class)->state('with-measurables')->create([
            'hero_class_id' => $rangerClass->id
        ]);

        $sorcererClass = HeroClass::sorcerer();
        $this->sorcerer = factory(Hero::class)->state('with-measurables')->create([
            'hero_class_id' => $sorcererClass->id
        ]);

    }

    /**
     * @test
     */
    public function a_hero_measurable_will_have_a_cost_to_raise()
    {
        /** @var Measurable $measurable */
        $measurable = $this->warrior->measurables->first();
        $this->assertEquals(0, $measurable->amount_raised);
        $this->assertGreaterThan(0, $measurable->getCostToRaise());
        $this->assertLessThan(200, $measurable->getCostToRaise());
    }
}
