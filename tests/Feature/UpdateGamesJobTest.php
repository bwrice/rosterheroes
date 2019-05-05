<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\DataTransferObjects\TeamDTO;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdateGamesJob;
use Carbon\CarbonImmutable;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateGamesJobTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_create_new_games()
    {
        $nfl = League::mlb();
        $dayStartOfYear = $nfl->getBehavior()->getDayOfYearStart();
        $now = CarbonImmutable::now();
        $testNow = $now->dayOfYear($dayStartOfYear + 10);
        CarbonImmutable::setTestNow($testNow);

        $gameIDs = [
            uniqid(),
            uniqid(),
            uniqid()
        ];

        $startsAt = $now->addDays(5);

        $gameDTOs = collect();

        foreach($gameIDs as $externalGameID) {
            $homeTeamID = uniqid();
            $homeTeam = factory(Team::class)->create([
                'league_id' => $nfl->id,
                'external_id' => $homeTeamID
            ]);
            $awayTeamID = uniqid();
            $awayTeam = factory(Team::class)->create([
                'league_id' => $nfl->id,
                'external_id' => $awayTeamID
            ]);
            $gameDTO = new GameDTO($startsAt, $homeTeam, $awayTeam, $externalGameID);
            $gameDTOs->push($gameDTO);
        }

        $integration = new MockIntegration(null, null, $gameDTOs);
        app()->instance(StatsIntegration::class, $integration);

        UpdateGamesJob::dispatchNow($nfl);

        $games = Game::query()->whereIn('external_id', $gameIDs)->get();

        $this->assertEquals(3, $games->count());
        $games->each(function (Game $game) use ($nfl, $startsAt) {
            $this->assertEquals($startsAt->timestamp, $game->starts_at->timestamp);
            $this->assertEquals($game->awayTeam->league_id, $nfl->id);
            $this->assertEquals($game->homeTeam->league_id, $nfl->id);
        });

    }

    /**
     * @test
     */
    public function it_will_update_games_that_change()
    {
        $nhl = League::nhl();
        $dayStartOfYear = $nhl->getBehavior()->getDayOfYearStart();
        $now = CarbonImmutable::now();
        $testNow = $now->dayOfYear($dayStartOfYear + 10);
        CarbonImmutable::setTestNow($testNow);

        $gameOneID = uniqid();
        $gameTwoID = uniqid();

        $originalGameIDs = [
            $gameOneID,
            $gameTwoID
        ];

        $startsAt = $now->addDays(5);

        $gameDTOs = collect();

        foreach($originalGameIDs as $originalGameID) {
            $homeTeamID = uniqid();
            $homeTeam = factory(Team::class)->create([
                'league_id' => $nhl->id,
                'external_id' => $homeTeamID
            ]);
            $awayTeamID = uniqid();
            $awayTeam = factory(Team::class)->create([
                'league_id' => $nhl->id,
                'external_id' => $awayTeamID
            ]);
            $gameDTO = new GameDTO($startsAt, $homeTeam, $awayTeam, $originalGameID);
            $gameDTOs->push($gameDTO);
        }

        $integration = new MockIntegration(null, null, $gameDTOs);
        app()->instance(StatsIntegration::class, $integration);

        UpdateGamesJob::dispatchNow($nhl);

        $games = Game::query()->whereIn('external_id', $originalGameIDs)->get();

        $this->assertEquals(3, $games->count());
        $games->each(function (Game $game) use ($nhl, $startsAt) {
            $this->assertEquals($startsAt->timestamp, $game->starts_at->timestamp);
            $this->assertEquals($game->awayTeam->league_id, $nhl->id);
            $this->assertEquals($game->homeTeam->league_id, $nhl->id);
        });


    }
}
