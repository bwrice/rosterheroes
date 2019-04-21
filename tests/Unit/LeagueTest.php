<?php

namespace Tests\Unit;

use App\Domain\Models\League;
use Carbon\CarbonImmutable;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueTest extends TestCase
{
    /**
     * @test
     */
    public function nfl_is_live_in_november()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-11-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nfl = League::nfl();
        $this->assertTrue($nfl->isLive());
    }

    /**
     * @test
     */
    public function nfl_is_live_in_january()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-01-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nfl = League::nfl();
        $this->assertTrue($nfl->isLive());
    }

    /**
     * @test
     */
    public function nfl_is_not_live_in_june()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-06-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nfl = League::nfl();
        $this->assertFalse($nfl->isLive());
    }

    /**
     * @test
     */
    public function mlb_is_live_in_july()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-07-15');
        CarbonImmutable::setTestNow($novemberDate);
        $mlb = League::mlb();
        $this->assertTrue($mlb->isLive());
    }

    /**
     * @test
     */
    public function mlb_is_not_live_in_february()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-02-15');
        CarbonImmutable::setTestNow($novemberDate);
        $mlb = League::mlb();
        $this->assertFalse($mlb->isLive());
    }

    /**
     * @test
     */
    public function mlb_is_not_live_in_december()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-12-15');
        CarbonImmutable::setTestNow($novemberDate);
        $mlb = League::mlb();
        $this->assertFalse($mlb->isLive());
    }

    /**
     * @test
     */
    public function nba_is_live_in_december()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-12-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nba = League::nba();
        $this->assertTrue($nba->isLive());
    }

    /**
     * @test
     */
    public function nba_is_live_in_may()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-05-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nba = League::nba();
        $this->assertTrue($nba->isLive());
    }

    /**
     * @test
     */
    public function nba_is_not_live_in_september()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-09-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nba = League::nba();
        $this->assertFalse($nba->isLive());
    }

    /**
     * @test
     */
    public function nhl_is_live_in_december()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-12-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nhl = League::nhl();
        $this->assertTrue($nhl->isLive());
    }

    /**
     * @test
     */
    public function nhl_is_live_in_may()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-05-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nhl = League::nhl();
        $this->assertTrue($nhl->isLive());
    }

    /**
     * @test
     */
    public function nhl_is_not_live_in_september()
    {
        $year = CarbonImmutable::now()->year;
        $novemberDate = CarbonImmutable::parse($year . '-09-15');
        CarbonImmutable::setTestNow($novemberDate);
        $nhl = League::nhl();
        $this->assertFalse($nhl->isLive());
    }

    /**
     * @test
     */
    public function a_league_that_spans_over_year_change_will_return_the_correct_season()
    {
        $year = CarbonImmutable::now()->year;
        $januaryDate = CarbonImmutable::parse($year . '-01-15');
        CarbonImmutable::setTestNow($januaryDate);
        $nfl = League::nfl();
        $this->assertEquals($year - 1, $nfl->getBehavior()->getSeason());
    }

    /**
     * @test
     */
    public function a_league_that_doesnt_span_over_year_will_return_the_correct_season()
    {
        $year = CarbonImmutable::now()->year;
        $june = CarbonImmutable::parse($year . '-06-15');
        CarbonImmutable::setTestNow($june);
        $mlb = League::mlb();
        $this->assertEquals($year, $mlb->getBehavior()->getSeason());
    }

    /**
     * @test
     */
    public function a_league_thats_ended_will_return_the_correct_season()
    {
        $year = CarbonImmutable::now()->year;
        $april = CarbonImmutable::parse($year . '-04-15');
        CarbonImmutable::setTestNow($april);
        $nfl = League::nfl();
        $this->assertEquals($year, $nfl->getBehavior()->getSeason());
    }
}
