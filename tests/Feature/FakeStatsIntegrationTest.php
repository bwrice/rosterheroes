<?php

namespace Tests\Feature;

use App\External\Stats\FakeStats\BuildFakePlayerGameLogDTO;
use App\External\Stats\FakeStats\FakeStatsIntegration;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FakeStatsIntegrationTest extends TestCase
{
    /**
     * @return FakeStatsIntegration
     */
    protected function getDomainAction()
    {
        return app(FakeStatsIntegration::class);
    }

    /**
     * @test
     */
    public function it_will_call_the_build_fake_game_log_dto_action_for_each_valid_player_for_the_game()
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

        $mockAction = \Mockery::mock(BuildFakePlayerGameLogDTO::class)
            ->shouldReceive('execute')
            ->times($homeTeamPlayersCount + $awayTeamPlayerCount)
            ->getMock();

        app()->instance(BuildFakePlayerGameLogDTO::class, $mockAction);

        $this->getDomainAction()->getGameLogDTOs($game, 0);
    }
}
