<?php

namespace Tests\Feature;

use App\Domain\Actions\DisableSpiritsForGame;
use App\Domain\Actions\UpdateSingleGame;
use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\ExternalGame;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\StatsIntegrationType;
use App\Domain\Models\Week;
use App\External\Stats\StatsIntegration;
use App\Factories\Models\GameFactory;
use App\Factories\Models\TeamFactory;
use App\Jobs\CreateSpiritsForGameJob;
use App\Jobs\DisableSpiritsForGameJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateSingleGameTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var StatsIntegrationType */
    protected $integrationType;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->states('as-current')->create();

        $this->integrationType = StatsIntegrationType::query()->create([
            'name' => Str::random(10)
        ]);

        $this->mock(StatsIntegration::class)
            ->shouldReceive('getIntegrationType')
            ->andReturn($this->integrationType);
    }

    /**
     * @return UpdateSingleGame
     */
    protected function getDomainAction()
    {
        return app(UpdateSingleGame::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_new_game_if_it_does_not_exist()
    {
        $teamFactory = TeamFactory::new()->forLeague(League::MLB);

        $homeTeam = $teamFactory->create();
        $awayTeam = $teamFactory->create();

        $gameDTO = new GameDTO(now(), $homeTeam, $awayTeam, uniqid(), Game::SCHEDULE_STATUS_NORMAL);

        $this->getDomainAction()->execute($gameDTO);

        $game = Game::query()->forIntegration($this->integrationType->id, $gameDTO->getExternalID())->first();
        $this->assertNotNull($game);
    }

    /**
     * @test
     */
    public function it_will_update_an_existing_game()
    {
        $startsAt = now()->addWeeks(5);
        $game = GameFactory::new()->withStartTime($startsAt)->create([
            'schedule_status' => Game::SCHEDULE_STATUS_NORMAL
        ]);

        $externalID = uniqid();

        $externalGame = ExternalGame::query()->create([
            'game_id' => $game->id,
            'integration_type_id' => $this->integrationType->id,
            'external_id' => $externalID
        ]);

        $updatedStarTime = $startsAt->clone()->addDays(5);

        $updatedGameDTO = new GameDTO($updatedStarTime, $game->homeTeam, $game->awayTeam, $externalID, Game::SCHEDULE_STATUS_NORMAL);
        $game->externalGames()->save($externalGame);

        $this->getDomainAction()->execute($updatedGameDTO);

        $game = $game->fresh();
        $this->assertEquals($game->starts_at->timestamp, $updatedStarTime->timestamp);
    }

    /**
     * @test
     */
    public function it_will_dispatch_disable_spirits_for_game_job_if_updating_a_game_out_of_weeks_range()
    {
        // Make start time after week locks
        $startsAt = $this->week->adventuring_locks_at->clone()->addHours(2);

        $game = GameFactory::new()->withStartTime($startsAt)->create([
            'schedule_status' => Game::SCHEDULE_STATUS_NORMAL
        ]);

        $externalID = uniqid();

        $externalGame = ExternalGame::query()->create([
            'game_id' => $game->id,
            'integration_type_id' => $this->integrationType->id,
            'external_id' => $externalID
        ]);

        // Move start to before week locks
        $updatedStarTime = $startsAt->clone()->subHours(5);

        $updatedGameDTO = new GameDTO($updatedStarTime, $game->homeTeam, $game->awayTeam, $externalID, Game::SCHEDULE_STATUS_NORMAL);

        Queue::fake();

        $this->getDomainAction()->execute($updatedGameDTO);

        Queue::assertPushed(DisableSpiritsForGameJob::class, function (DisableSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_disable_spirits_if_the_updated_game_time_is_still_valid_for_weekly_spirits()
    {
        // Make start time after week locks
        $startsAt = $this->week->adventuring_locks_at->clone()->addHours(2);

        $game = GameFactory::new()->withStartTime($startsAt)->create([
            'schedule_status' => Game::SCHEDULE_STATUS_NORMAL
        ]);

        /** @var StatsIntegrationType $integrationType */
        $integrationType = StatsIntegrationType::query()->first();
        $externalID = uniqid();

        $externalGame = ExternalGame::query()->create([
            'game_id' => $game->id,
            'integration_type_id' => $this->integrationType->id,
            'external_id' => $externalID
        ]);

        // Move start to before week locks
        $updatedStarTime = $startsAt->clone()->addHours(2);

        $updatedGameDTO = new GameDTO($updatedStarTime, $game->homeTeam, $game->awayTeam, $externalID, Game::SCHEDULE_STATUS_NORMAL);

        Queue::fake();

        $this->getDomainAction()->execute($updatedGameDTO);

        Queue::assertNotPushed(DisableSpiritsForGameJob::class, function (DisableSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_disable_spirits_for_game_job_if_updating_a_game_that_is_postponed()
    {
        // Make start time after week locks
        $startsAt = $this->week->adventuring_locks_at->clone()->addHours(2);

        $game = GameFactory::new()->withStartTime($startsAt)->create([
            'schedule_status' => Game::SCHEDULE_STATUS_NORMAL
        ]);

        $externalID = uniqid();

        $externalGame = ExternalGame::query()->create([
            'game_id' => $game->id,
            'integration_type_id' => $this->integrationType->id,
            'external_id' => $externalID
        ]);

        // Change status to postponed
        $updatedGameDTO = new GameDTO($startsAt, $game->homeTeam, $game->awayTeam, $externalID, Game::SCHEDULE_STATUS_POSTPONED);

        Queue::fake();

        $this->getDomainAction()->execute($updatedGameDTO);

        Queue::assertPushed(DisableSpiritsForGameJob::class, function (DisableSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_create_spirits_for_game_if_a_new_game_is_valid_for_the_week()
    {
        $teamFactory = TeamFactory::new()->forLeague(League::NHL);

        $homeTeam = $teamFactory->create();
        $awayTeam = $teamFactory->create();

        $startsAt = $this->week->adventuring_locks_at->addHour();

        $gameDTO = new GameDTO($startsAt, $homeTeam, $awayTeam, uniqid(), Game::SCHEDULE_STATUS_NORMAL);

        Queue::fake();

        $this->getDomainAction()->execute($gameDTO);

        $game = Game::query()->forIntegration($this->integrationType->id, $gameDTO->getExternalID())->first();

        Queue::assertPushed(CreateSpiritsForGameJob::class, function (CreateSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }
}
