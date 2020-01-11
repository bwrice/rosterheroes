<?php

namespace Tests\Unit;

use App\Domain\Behaviors\StatTypes\Baseball\InningPitchedBehavior;
use App\Domain\Behaviors\StatTypes\InningsPitchedCalculator;
use App\Domain\Behaviors\StatTypes\MultiplierCalculator;
use Illuminate\Validation\Rules\In;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InningsPitchedBehaviorTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_calculate_full_innings_correctly()
    {
        /** @var InningPitchedBehavior $behavior */
        $behavior = app(InningPitchedBehavior::class);
        $pointsPer = $behavior->getPointsPer();

        $total = $behavior->getTotalPoints(5);
        $this->assertTrue(abs(($pointsPer * 5) - $total) < PHP_FLOAT_EPSILON);
    }

    /**
     * @test
     */
    public function it_will_calculate_one_third_inning_pitched_correctly()
    {
        /** @var InningPitchedBehavior $behavior */
        $behavior = app(InningPitchedBehavior::class);
        $pointsPer = $behavior->getPointsPer();

        $total = $behavior->getTotalPoints(5.1);
        $expected = round(5 * $pointsPer + ((1/3) * $pointsPer), 2);
        $this->assertTrue(abs($expected - $total) < PHP_FLOAT_EPSILON);
    }

    /**
     * @test
     */
    public function it_will_calculate_two_thirds_inning_pitched_correctly()
    {
        /** @var InningPitchedBehavior $behavior */
        $behavior = app(InningPitchedBehavior::class);
        $pointsPer = $behavior->getPointsPer();

        $total = $behavior->getTotalPoints(5.2);
        $expected = round(5 * $pointsPer + ((2/3) * $pointsPer), 2);
        $this->assertTrue(abs($expected - $total) < PHP_FLOAT_EPSILON);
    }
}
