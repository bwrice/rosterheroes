<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\External\Stats\FakeStats\CreateFakeStatAmountDTOsForPlayer;
use App\External\Stats\FakeStats\FakeStatsIntegration;
use App\External\Stats\StatsIntegration;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class FakeStatsIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FakeStatsIntegration
     */
    protected function getIntegration()
    {
        return app(FakeStatsIntegration::class);
    }

    /**
     * @test
     */
    public function it_will_be_resolved_out_of_the_container_if_the_config_key_is_set_to_match()
    {
        config(['stats-integration.driver' => FakeStatsIntegration::ENV_KEY]);

        $integration = app(StatsIntegration::class);
        $this->assertTrue($integration instanceof FakeStatsIntegration);
    }

    /**
     * @test
     */
    public function it_will_execute_the_get_fake_stats_action_for_each_valid_player_for_the_game()
    {
        $game = GameFactory::new()->create();
        $homeTeamPlayerFactory = PlayerFactory::new()->withTeamID($game->home_team_id);
        $homeTeamPlayersCount = rand(1,3);
        for($i = 1; $i <= $homeTeamPlayersCount; $i++) {
            $homeTeamPlayerFactory->create();
        }
        $awayTeamPlayerFactor = PlayerFactory::new()->withTeamID($game->away_team_id);
        $awayTeamPlayerCount = rand(1,3);
        for($i = 1; $i <= $awayTeamPlayerCount; $i++) {
            $awayTeamPlayerFactor->create();
        }

        $mockAction = \Mockery::mock(CreateFakeStatAmountDTOsForPlayer::class)
            ->shouldReceive('execute')
            ->times($homeTeamPlayersCount + $awayTeamPlayerCount)
            ->getMock();

        app()->instance(CreateFakeStatAmountDTOsForPlayer::class, $mockAction);

        $this->getIntegration()->getGameLogDTOs($game, 0);
    }

    /**
     * @test
     */
    public function it_will_return_a_game_log_DTO_collection_with_the_expected_amount_of_DTOs()
    {
        $game = GameFactory::new()->create();
        $homeTeamPlayerFactory = PlayerFactory::new()->withTeamID($game->home_team_id);
        $homeTeamPlayersCount = rand(1,3);
        for($i = 1; $i <= $homeTeamPlayersCount; $i++) {
            $homeTeamPlayerFactory->create();
        }
        $awayTeamPlayerFactor = PlayerFactory::new()->withTeamID($game->away_team_id);
        $awayTeamPlayerCount = rand(1,3);
        for($i = 1; $i <= $awayTeamPlayerCount; $i++) {
            $awayTeamPlayerFactor->create();
        }

        $mockAction = \Mockery::mock(CreateFakeStatAmountDTOsForPlayer::class)
            ->shouldReceive('execute')
            ->times($homeTeamPlayersCount + $awayTeamPlayerCount)
            ->andReturn(collect())
            ->getMock();

        app()->instance(CreateFakeStatAmountDTOsForPlayer::class, $mockAction);

        $gameLogDTOs = $this->getIntegration()->getGameLogDTOs($game, 0);
        $this->assertEquals($homeTeamPlayersCount + $awayTeamPlayerCount, $gameLogDTOs->count());
    }

    /**
     * @test
     */
    public function it_will_have_a_game_over_value_of_false_on_the_game_log_dto_collection_if_the_game_hasnt_started_yet()
    {
        $startsAt = Date::now()->addMinutes(1);
        $game = GameFactory::new()->withStartTime($startsAt)->create();
        PlayerFactory::new()->withTeamID($game->home_team_id)->create();
        PlayerFactory::new()->withTeamID($game->away_team_id)->create();

        $mockAction = \Mockery::mock(CreateFakeStatAmountDTOsForPlayer::class)
            ->shouldReceive('execute')
            ->times(2)
            ->andReturn(collect())
            ->getMock();

        app()->instance(CreateFakeStatAmountDTOsForPlayer::class, $mockAction);

        $gameLogDTOs = $this->getIntegration()->getGameLogDTOs($game, 0);
        $this->assertFalse($gameLogDTOs->isGameOver());
    }

    /**
     * @test
     */
    public function it_will_have_a_game_over_value_of_true_on_the_game_log_dto_collection_if_game_started_over_three_hours_ago()
    {
        $startsAt = Date::now()->subHours(3)->subMinutes(1);
        $game = GameFactory::new()->withStartTime($startsAt)->create();
        PlayerFactory::new()->withTeamID($game->home_team_id)->create();
        PlayerFactory::new()->withTeamID($game->away_team_id)->create();

        $mockAction = \Mockery::mock(CreateFakeStatAmountDTOsForPlayer::class)
            ->shouldReceive('execute')
            ->times(2)
            ->andReturn(collect())
            ->getMock();

        app()->instance(CreateFakeStatAmountDTOsForPlayer::class, $mockAction);

        $gameLogDTOs = $this->getIntegration()->getGameLogDTOs($game, 0);
        $this->assertTrue($gameLogDTOs->isGameOver());
    }
}
