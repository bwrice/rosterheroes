<?php

namespace Tests\Unit;

use App\Domain\Actions\RaiseMeasurableAction;
use App\Domain\Interfaces\HasMeasurables;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Exceptions\RaiseMeasurableException;
use App\Facades\CurrentWeek;
use App\Nova\HeroClass;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RaiseMeasurableActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var HeroPost */
    protected $heroPost;

    /** @var RaiseMeasurableAction */
    protected $raiseMeasurableAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        $this->raiseMeasurableAction = app(RaiseMeasurableAction::class);
    }

    /**
     * @test
     */
    public function raising_measurable_when_week_is_locked_will_throw_an_exception()
    {
        CurrentWeek::partialMock()->shouldReceive('adventuringLocked')->andReturn(true);
        /** @var Measurable $measurable */
        $measurable = $this->hero->measurables()->inRandomOrder()->first();
        try {
            /** @var RaiseMeasurableAction $domainAction */
            $domainAction = app(RaiseMeasurableAction::class);
            $domainAction->execute($measurable, 1);
        } catch (RaiseMeasurableException $exception) {
            $this->assertEquals(RaiseMeasurableException::CODE_WEEK_LOCKED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_amount_is_not_positive()
    {
        CurrentWeek::partialMock()->shouldReceive('adventuringLocked')->andReturn(false);
        $agilityMeasurable = $this->hero->getMeasurable(MeasurableType::AGILITY);
        $startingAmount = $agilityMeasurable->amount_raised;
        foreach ([-5, 0] as $amount) {
            try {

                $this->raiseMeasurableAction->execute($agilityMeasurable, $amount);

            } catch (RaiseMeasurableException $exception) {
                $this->assertEquals(RaiseMeasurableException::CODE_NON_POSITIVE_NUMBER, $exception->getCode());
                $this->assertEquals($startingAmount, $agilityMeasurable->fresh()->amount_raised);
                continue;
            }
            $this->fail("Exception not thrown");
        }
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_experience_cost_is_too_high()
    {
        CurrentWeek::partialMock()->shouldReceive('adventuringLocked')->andReturn(false);
        $staminaMeasurable = $this->hero->getMeasurable(MeasurableType::STAMINA);
        $startingAmount = $staminaMeasurable->amount_raised;

        $squad = $this->hero->squad;
        $squad->experience = $squadTotalExperience = 100;
        $squad->save();

        $raiseAmount = 99;
        // assert passing no value, ie default of 1 to getCostToRaise() is less than squad total experience
        $this->assertLessThan($squadTotalExperience, $staminaMeasurable->getCostToRaise());
        $this->assertGreaterThan($this->hero->availableExperience(), $staminaMeasurable->getCostToRaise($raiseAmount));

        try {

            $this->raiseMeasurableAction->execute($staminaMeasurable, $raiseAmount);

        } catch (RaiseMeasurableException $exception) {
            $this->assertEquals(RaiseMeasurableException::INSUFFICIENT_EXPERIENCE, $exception->getCode());
            $this->assertEquals($startingAmount, $staminaMeasurable->fresh()->amount_raised);
            return;
        }
        $this->fail("Exception not thrown");
    }
    /**
     * @test
     */
    public function it_will_raise_a_measurable_for_a_hero()
    {
        CurrentWeek::partialMock()->shouldReceive('adventuringLocked')->andReturn(false);
        // Give squad plenty of experience
        $squad = $this->hero->squad;
        $squad->experience = 999999;
        $squad->save();

        $honorMeasurable = $this->hero->getMeasurable(MeasurableType::HONOR);

        $startingAmount = $honorMeasurable->amount_raised;
        $raiseAmount = 3;
        $costToRaise = $honorMeasurable->getCostToRaise($raiseAmount);
        $this->assertGreaterThan($costToRaise, $this->hero->availableExperience());
        $this->raiseMeasurableAction->execute($honorMeasurable, $raiseAmount);

        $newRaisedAmount = $honorMeasurable->fresh()->amount_raised;
        $this->assertEquals($newRaisedAmount - $startingAmount, $raiseAmount);

        $honorMeasurable = $honorMeasurable->fresh();
        $costToRaise = $honorMeasurable->getCostToRaise($raiseAmount);
        $this->assertGreaterThan($costToRaise, $this->hero->availableExperience());

        // Raise it again, just to be sure that starting from zero doesn't matter
        $this->raiseMeasurableAction->execute($honorMeasurable, $raiseAmount);

        $finalRaisedAmount = $honorMeasurable->fresh()->amount_raised;
        $this->assertEquals($finalRaisedAmount - $startingAmount, $raiseAmount + $raiseAmount);
    }
}
