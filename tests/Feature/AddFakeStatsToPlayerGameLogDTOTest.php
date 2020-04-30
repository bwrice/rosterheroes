<?php

namespace Tests\Feature;

use App\Domain\Behaviors\Positions\RunningBackBehavior;
use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
use App\External\Stats\FakeStats\CreateFakeStatAmountDTOsForPlayer;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerStatFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddFakeStatsToPlayerGameLogDTOTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateFakeStatAmountDTOsForPlayer
     */
    protected function getDomainAction()
    {
        return app(CreateFakeStatAmountDTOsForPlayer::class);
    }

    /**
     * @test
     */
    public function it_will_return_a_dto_with_no_stats_if_the_player_has_no_positions()
    {
        $player = PlayerFactory::new()->create();
        $this->assertEquals(0, $player->positions()->count());

        $statAmountDTOs = $this->getDomainAction()->execute($player);
        $this->assertEquals(0, $statAmountDTOs->count());
    }

    /**
     * @test
     */
    public function it_will_get_stats_from_a_historic_game_log_if_enough_game_logs_exist_and_add_them_to_the_game_log_dto()
    {
        $yardsAmount = rand(50, 150);
        $statsFactory = PlayerStatFactory::new()->forStatType(StatType::RUSH_YARD)->withAmount($yardsAmount);
        /** @var Position $position */
        $position = Position::query()->where('name', '=', Position::RUNNING_BACK)->first();
        $playerFactory = PlayerFactory::new()->withPosition($position);
        $playerGameLog = PlayerGameLogFactory::new()->withPlayer($playerFactory)->withStats(collect([$statsFactory]))->create();

        /*
         * Do some setup so that our domain action only needs 1 historic game log to use for fake stats
         */
        $mockRunningBackBehavior = \Mockery::mock(RunningBackBehavior::class)
            ->shouldReceive('getGamesPerSeason')
            ->andReturn(1)
            ->getMock();
        app()->instance(RunningBackBehavior::class, $mockRunningBackBehavior);

        $domainAction = $this->getDomainAction();
        $domainAction->setAbsoluteMinGameLogsCount(0);

        // Execute the action
        $statAmountDTOs = $domainAction->execute($playerGameLog->player);

        // Confirm the stat-amount-dtos are set to what you put in the mock
        $this->assertEquals(1, $statAmountDTOs->count());
        /** @var StatAmountDTO $resultantStatAmountDTO */
        $resultantStatAmountDTO = $statAmountDTOs->first();
        $this->assertEquals($yardsAmount, $resultantStatAmountDTO->getAmount());
        $this->assertEquals(StatType::RUSH_YARD, $resultantStatAmountDTO->getStatType()->name);
    }
}
