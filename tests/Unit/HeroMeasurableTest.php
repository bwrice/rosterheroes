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

    /**
     * @test
     */
    public function a_warrior_starting_strength_is_higher_than_a_sorcerers()
    {
        $warriorStrength = $this->warrior->getMeasurable(MeasurableType::STRENGTH);
        $sorcererStrength = $this->sorcerer->getMeasurable(MeasurableType::STRENGTH);

        $this->assertEquals(0, $warriorStrength->amount_raised);
        $this->assertEquals(0, $sorcererStrength->amount_raised);

        $this->assertGreaterThan($sorcererStrength->getCurrentAmount(), $warriorStrength->getCurrentAmount());
    }

    /**
     * @test
     */
    public function a_sorcerers_starting_intelligence_is_higher_than_a_rangers()
    {
        $sorcererIntelligence = $this->sorcerer->getMeasurable(MeasurableType::INTELLIGENCE);
        $rangerIntelligence = $this->ranger->getMeasurable(MeasurableType::INTELLIGENCE);

        $this->assertEquals(0, $sorcererIntelligence->amount_raised);
        $this->assertEquals(0, $rangerIntelligence->amount_raised);

        $this->assertGreaterThan($rangerIntelligence->getCurrentAmount(), $sorcererIntelligence->getCurrentAmount());
    }

    /**
     * @test
     */
    public function a_rangers_starting_focus_is_higher_than_a_warriors()
    {
        $rangerFocus = $this->ranger->getMeasurable(MeasurableType::FOCUS);
        $warriorFocus = $this->warrior->getMeasurable(MeasurableType::FOCUS);

        $this->assertEquals(0, $rangerFocus->amount_raised);
        $this->assertEquals(0, $warriorFocus->amount_raised);

        $this->assertGreaterThan($warriorFocus->getCurrentAmount(), $rangerFocus->getCurrentAmount());
    }


}
