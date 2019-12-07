<?php

namespace Tests\Unit;

use App\Domain\Actions\UpdateHistoricGameLogsAction;
use App\Domain\Collections\LeagueCollection;
use App\Domain\Models\League;
use App\External\Stats\StatsIntegration;
use App\ExternalGame;
use App\Jobs\UpdateHistoricPlayerGameLogsJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateHistoricGameLogsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var ExternalGame */
    protected $externalGame;

    /** @var League */
    protected $league;

    /** @var StatsIntegration */
    protected $statsIntegration;

    public function setUp(): void
    {
        parent::setUp();
        /** @var StatsIntegration $statsIntegration */
        $this->statsIntegration = app(StatsIntegration::class);

        $this->league = League::query()->inRandomOrder()->first();

        $this->externalGame = factory(ExternalGame::class)->create([
            'integration_type_id' => $this->statsIntegration->getIntegrationType()->id
        ]);

        $this->league = $this->externalGame->game->homeTeam->league;

        $game = $this->externalGame->game;
        $game->starts_at = Date::now()->subWeeks(2);
        $game->save();
    }

    /**
    * @test
    */
    public function it_will_only_create_jobs_for_games_that_have_a_significant_past_start_date()
    {
        /** @var ExternalGame $nearFutureExternalGame */
        $nearFutureExternalGame = factory(ExternalGame::class)->create([
            'integration_type_id' => $this->statsIntegration->getIntegrationType()->id
        ]);

        $nearFutureGame = $nearFutureExternalGame->game;
        $nearFutureGame->starts_at = Date::now()->subHours(2);
        $nearFutureGame->save();

        Queue::fake();

        /** @var UpdateHistoricGameLogsAction $domainAction */
        $domainAction = app(UpdateHistoricGameLogsAction::class);

        $domainAction->execute();

        Queue::assertPushed(UpdateHistoricPlayerGameLogsJob::class, function (UpdateHistoricPlayerGameLogsJob $job){
            return $this->externalGame->game->id === $job->getGame()->id;
        });

        Queue::assertNotPushed(UpdateHistoricPlayerGameLogsJob::class, function (UpdateHistoricPlayerGameLogsJob $job) use ($nearFutureGame) {
            return $nearFutureGame->id === $job->getGame()->id;
        });
    }

    /**
     * @test
     */
    public function it_will_only_create_jobs_for_the_leagues_passed_as_argument()
    {
        /** @var League $differentLeague */
        $differentLeague = League::query()->where('id', '!=', $this->league->id)->inRandomOrder()->first();

        /** @var ExternalGame $differentLeagueExternalGame */
        $differentLeagueExternalGame = factory(ExternalGame::class)->create([
            'integration_type_id' => $this->statsIntegration->getIntegrationType()->id
        ]);

        $game = $differentLeagueExternalGame->game;
        $game->starts_at = Date::now()->subWeeks(2);
        $game->save();

        $homeTeam = $game->homeTeam;
        $homeTeam->league_id = $differentLeague->id;
        $homeTeam->save();

        $awayTeam = $game->awayTeam;
        $awayTeam->league_id = $differentLeague->id;
        $awayTeam->save();

        $leagues = (new LeagueCollection())->push($this->league);

        Queue::fake();

        /** @var UpdateHistoricGameLogsAction $domainAction */
        $domainAction = app(UpdateHistoricGameLogsAction::class);
        $domainAction->execute($leagues);

        Queue::assertPushed(UpdateHistoricPlayerGameLogsJob::class, function (UpdateHistoricPlayerGameLogsJob $job){
            return $this->externalGame->game->id === $job->getGame()->id;
        });

        Queue::assertNotPushed(UpdateHistoricPlayerGameLogsJob::class, function (UpdateHistoricPlayerGameLogsJob $job) use ($differentLeagueExternalGame) {
            return $differentLeagueExternalGame->game->id === $job->getGame()->id;
        });
    }

    /**
    * @test
    */
    public function it_will_not_create_jobs_for_finalized_games_if_force_is_not_set_to_true()
    {
        /** @var ExternalGame $finalizedExternalGame */
        $finalizedExternalGame = factory(ExternalGame::class)->create([
            'integration_type_id' => $this->statsIntegration->getIntegrationType()->id
        ]);

        $game = $finalizedExternalGame->game;
        $game->starts_at = Date::now()->subWeeks(2);

        //Finalize game
        $game->finalized_at = Date::now()->subHours(12);
        $game->save();

        Queue::fake();

        /** @var UpdateHistoricGameLogsAction $domainAction */
        $domainAction = app(UpdateHistoricGameLogsAction::class);
        $domainAction->execute();

        Queue::assertPushed(UpdateHistoricPlayerGameLogsJob::class, function (UpdateHistoricPlayerGameLogsJob $job){
            return $this->externalGame->game->id === $job->getGame()->id;
        });

        Queue::assertNotPushed(UpdateHistoricPlayerGameLogsJob::class, function (UpdateHistoricPlayerGameLogsJob $job) use ($finalizedExternalGame) {
            return $finalizedExternalGame->game->id === $job->getGame()->id;
        });
    }
}
