<?php

namespace Tests\Unit;

use App\Domain\Behaviors\StatTypes\InningsPitchedCalculator;
use App\Domain\Behaviors\StatTypes\MultiplierCalculator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InningsPitchedCalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_calculate_full_innings_correctly()
    {
        $calculator = new InningsPitchedCalculator(new MultiplierCalculator(10));

        $total = $calculator->total(5);
        $this->assertTrue(abs(50 - $total) < PHP_FLOAT_EPSILON);
    }

    /**
     * @test
     */
    public function it_will_calculate_one_third_inning_pitched_correctly()
    {
        $pointsPer = 10;
        $calculator = new InningsPitchedCalculator(new MultiplierCalculator($pointsPer));


        $total = $calculator->total(5.1);
        $expected = round(5 * $pointsPer + ((1/3) * $pointsPer), 2);
        $this->assertTrue(abs($expected - $total) < PHP_FLOAT_EPSILON);
    }

    /**
     * @test
     */
    public function it_will_calculate_two_thirds_inning_pitched_correctly()
    {
        $pointsPer = 10;
        $calculator = new InningsPitchedCalculator(new MultiplierCalculator($pointsPer));


        $total = $calculator->total(5.2);
        $expected = round(5 * $pointsPer + ((2/3) * $pointsPer), 2);
        $this->assertTrue(abs($expected - $total) < PHP_FLOAT_EPSILON);
    }
}
