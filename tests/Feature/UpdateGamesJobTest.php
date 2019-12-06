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
use Illuminate\Database\Eloquent\Builder;
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
            $homeTeam = factory(Team::class)->create([
                'league_id' => $nfl->id
            ]);
            $awayTeam = factory(Team::class)->create([
                'league_id' => $nfl->id
            ]);
            $gameDTO = new GameDTO($startsAt, $homeTeam, $awayTeam, $externalGameID);
            $gameDTOs->push($gameDTO);
        }

        $integration = new MockIntegration(null, null, $gameDTOs);
        app()->instance(StatsIntegration::class, $integration);

        UpdateGamesJob::dispatchNow($nfl);

        $games = Game::query()->whereHas('externalGames', function (Builder $builder) use ($integration, $gameIDs) {
            return $builder->where('integration_type_id', '=', $integration->getIntegrationType()->id)
                ->whereIn('external_id', $gameIDs);
        });

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
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $now = CarbonImmutable::now()
        ]);

        $externalID = uniqid();
        $fourHoursLater = $now->addHours(4);
        $gameDTO = new GameDTO(
            $fourHoursLater,
            factory(Team::class)->create(),
            factory(Team::class)->create(),
            $externalID
        );

        $integration = new MockIntegration(null, null, collect([$gameDTO]));

        $game->externalGames()->create([
            'integration_type_id' => $integration->getIntegrationType()->id,
            'external_id' => $externalID
        ]);

        app()->instance(StatsIntegration::class, $integration);

        $games = Game::query()->forIntegration($integration->getIntegrationType()->id, $externalID);
        $this->assertEquals(1, $games->count());

        /** @var Game $game */
        $game = $games->first();
        $this->assertEquals($now->timestamp, $game->starts_at->timestamp);

        // any league should work since we're mocking the integration response
        UpdateGamesJob::dispatchNow(League::query()->inRandomOrder()->first());

        $games = Game::query()->forIntegration($integration->getIntegrationType()->id, $externalID);
        $this->assertEquals(1, $games->count());

        /** @var Game $game */
        $game = $games->first();
        $this->assertEquals($fourHoursLater->timestamp, $game->starts_at->timestamp);
    }
}
