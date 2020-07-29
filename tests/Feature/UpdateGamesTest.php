<?php

namespace Tests\Feature;

use App\Domain\Actions\UpdateGames;
use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\External\Stats\StatsIntegration;
use App\Factories\Models\TeamFactory;
use App\Jobs\UpdateSingleGameJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UpdateGamesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return UpdateGames
     */
    protected function getDomainAction()
    {
        return app(UpdateGames::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_update_single_game_jobs()
    {
        /** @var League $league */
        $league = League::query()->inRandomOrder()->first();
        $teamFactory = TeamFactory::new()->forLeague($league->abbreviation);

        $gameOneExternalID = uniqid();
        $gameDTO1 = new GameDTO(now()->addWeeks(1), $teamFactory->create(), $teamFactory->create(), $gameOneExternalID, Game::SCHEDULE_STATUS_NORMAL);

        $gameTwoExternalID = uniqid();
        $gameDTO2 = new GameDTO(now()->addWeeks(1), $teamFactory->create(), $teamFactory->create(), $gameTwoExternalID, Game::SCHEDULE_STATUS_NORMAL);
        $DTOs = collect([$gameDTO1, $gameDTO2]);

        $this->mock(StatsIntegration::class)->shouldReceive('getGameDTOs')->andReturn($DTOs);

        Queue::fake();

        $this->getDomainAction()->execute($league);

        Queue::assertPushed(UpdateSingleGameJob::class, function (UpdateSingleGameJob $updateSingleGameJob) use ($gameDTO1) {
            return $updateSingleGameJob->gameDTO->getExternalID() === $gameDTO1->getExternalID();
        });

        Queue::assertPushed(UpdateSingleGameJob::class, function (UpdateSingleGameJob $updateSingleGameJob) use ($gameDTO2) {
            return $updateSingleGameJob->gameDTO->getExternalID() === $gameDTO2->getExternalID();
        });
    }
}
