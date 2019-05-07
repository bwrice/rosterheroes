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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateGamesJobTest extends TestCase
{
    use DatabaseTransactions;

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
        factory(Game::class)->create([
            'external_id' => $gameID = uniqid(),
            'starts_at' => $now = CarbonImmutable::now()
        ]);

        $games = Game::query()->where('external_id', '=', $gameID)->get();
        $this->assertEquals(1, $games->count());

        /** @var Game $game */
        $game = $games->first();
        $this->assertEquals($now->timestamp, $game->starts_at->timestamp);

        $fourHoursLater = $now->addHours(4);
        $gameDTO = new GameDTO(
            $fourHoursLater,
            factory(Team::class)->create(),
            factory(Team::class)->create(),
            $gameID
        );

        $integration = new MockIntegration(null, null, collect([$gameDTO]));
        app()->instance(StatsIntegration::class, $integration);

        // any league should work since we're mocking the integration response
        UpdateGamesJob::dispatchNow(League::query()->inRandomOrder()->first());

        $games = Game::query()->where('external_id', '=', $gameID)->get();
        $this->assertEquals(1, $games->count());

        /** @var Game $game */
        $game = $games->first();
        $this->assertEquals($fourHoursLater->timestamp, $game->starts_at->timestamp);
    }
}
