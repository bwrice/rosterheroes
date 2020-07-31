<?php

namespace Tests\Unit;

use App\Domain\Models\League;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueTest extends TestCase
{
    /**
     * @test
     * @param $abbreviation
     * @param $year
     * @param $month
     * @param $day
     * @param $expectedSeason
     * @dataProvider provides_a_league_will_return_the_correct_season
     */
    public function a_league_will_return_the_correct_season($abbreviation, $year, $month, $day, $expectedSeason)
    {
        $testNow = Date::create($year, $month, $day);
        Date::setTestNow($testNow);

        /** @var League $league */
        $league = League::query()->where('abbreviation', '=', $abbreviation)->first();
        $season = $league->getSeason();
        $this->assertEquals($expectedSeason, $season);
    }

    public function provides_a_league_will_return_the_correct_season()
    {
        return [
            League::NFL . '5-01-2020' => [
                'abbreviation' => League::NFL,
                'year' => 2020,
                'month' => 5,
                'day' => 1,
                'expectedSeason' => 2019
            ],
            League::NFL . '9-01-2020' => [
                'abbreviation' => League::NFL,
                'year' => 2020,
                'month' => 9,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NFL . '2-01-2021' => [
                'abbreviation' => League::NFL,
                'year' => 2021,
                'month' => 2,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::MLB . '3-01-2020' => [
                'abbreviation' => League::MLB,
                'year' => 2020,
                'month' => 3,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::MLB . '7-01-2020' => [
                'abbreviation' => League::MLB,
                'year' => 2020,
                'month' => 7,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::MLB . '10-01-2020' => [
                'abbreviation' => League::MLB,
                'year' => 2020,
                'month' => 10,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NBA . '10-01-2020' => [
                'abbreviation' => League::NBA,
                'year' => 2020,
                'month' => 10,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NBA . '3-01-2021' => [
                'abbreviation' => League::NBA,
                'year' => 2021,
                'month' => 3,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NBA . '7-01-2021' => [
                'abbreviation' => League::NBA,
                'year' => 2021,
                'month' => 7,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NHL . '10-01-2020' => [
                'abbreviation' => League::NHL,
                'year' => 2020,
                'month' => 10,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NHL . '3-01-2021' => [
                'abbreviation' => League::NHL,
                'year' => 2021,
                'month' => 3,
                'day' => 1,
                'expectedSeason' => 2020
            ],
            League::NHL . '7-01-2021' => [
                'abbreviation' => League::NHL,
                'year' => 2021,
                'month' => 7,
                'day' => 1,
                'expectedSeason' => 2020
            ],
        ];
    }
}
