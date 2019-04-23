<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdateGames;
use Carbon\CarbonImmutable;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateGamesTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_create_new_games()
    {
        $nfl = League::nfl();
        $dayStartOfYear = $nfl->getBehavior()->getDayOfYearStart();
        $now = CarbonImmutable::now();
        $testNow = $now->dayOfYear($dayStartOfYear + 10);
        CarbonImmutable::setTestNow($testNow);

        $this->assertTrue($nfl->isLive());
    }

    /**
     * @test
     */
    public function it_will_update_games_that_change()
    {

    }
}
