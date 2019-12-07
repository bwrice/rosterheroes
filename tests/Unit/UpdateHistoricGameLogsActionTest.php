<?php

namespace Tests\Unit;

use App\Domain\Actions\UpdateHistoricGameLogsAction;
use App\Domain\Collections\LeagueCollection;
use App\Domain\Models\League;
use App\External\Stats\StatsIntegration;
use App\ExternalGame;
use App\Jobs\UpdateHistoricPlayerGameLogsJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateHistoricGameLogsActionTest extends TestCase
{
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

        /** @var LeagueCollection $leagues */
        $leagues = League::all();
        $domainAction->execute($leagues);

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

    }

    /**
    * @test
    */
    public function it_will_not_create_jobs_for_finalized_games_if_force_is_false()
    {

    }
}
