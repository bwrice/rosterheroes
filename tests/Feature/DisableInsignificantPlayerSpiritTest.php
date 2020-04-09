<?php

namespace Tests\Feature;

use App\Domain\Actions\DisableInsignificantPlayerSpirit;
use App\Domain\Actions\DisablePlayerSpirit;
use App\Domain\Models\Position;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class DisableInsignificantPlayerSpiritTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_not_disable_a_player_spirit_without_previous_game_logs()
    {
        $game = GameFactory::new()->create([
            'starts_at' => Date::now()->addDays(5)
        ]);
        $player = PlayerFactory::new()->withPosition(Position::query()->inRandomOrder()->first())->create();
        $gameLogFactory = PlayerGameLogFactory::new()->forGame($game)->forPlayer($player);
        $playerSpirit = PlayerSpiritFactory::new()->withPlayerGameLog($gameLogFactory)->create();

        $disableSpy = \Mockery::spy(DisablePlayerSpirit::class);
        app()->instance(DisablePlayerSpirit::class, $disableSpy);

        /** @var DisableInsignificantPlayerSpirit $domainAction */
        $domainAction = app(DisableInsignificantPlayerSpirit::class);
        $result = $domainAction->execute($playerSpirit);

        $this->assertFalse($result);
        $disableSpy->shouldNotHaveReceived('execute');
    }

    /**
     * @test
     * @dataProvider provides_it_will_disable_player_spirits_with_multiple_games_without_any_stats
     * @param $positionName
     * @param $previousGamesCount
     */
    public function it_will_disable_player_spirits_with_multiple_games_without_any_stats($positionName, $previousGamesCount)
    {
        $game = GameFactory::new()->create([
            'starts_at' => Date::now()->addDays(5)
        ]);
        $player = PlayerFactory::new()->withPosition(Position::forName($positionName))->create();
        $baseGameLogFactory = PlayerGameLogFactory::new()->forPlayer($player);
        $playerSpiritsGameLog = $baseGameLogFactory->forGame($game);
        $playerSpirit = PlayerSpiritFactory::new()->withPlayerGameLog($playerSpiritsGameLog)->create();


        /*
         * Make game logs without stats
         */
        foreach (range(1, $previousGamesCount) as $count) {
            $game = GameFactory::new()->create([
                'starts_at' => Date::now()->subWeeks($count)
            ]);
            $baseGameLogFactory->forGame($game)->create();
        }

        /*
         * Make older game logs with stats
         */
        foreach (range(1, $previousGamesCount) as $count) {
            $game = GameFactory::new()->create([
                'starts_at' => Date::now()->subWeeks($count + 1 + $previousGamesCount)
            ]);
            $baseGameLogFactory->forGame($game)->withStats()->create();
        }

        // verify we have previous game logs and the one for the created player spirit
        $expectedLogsCount = (2 * $previousGamesCount) + 1;
        $this->assertEquals($expectedLogsCount, $player->playerGameLogs()->count());

        $disableSpy = \Mockery::spy(DisablePlayerSpirit::class);
        app()->instance(DisablePlayerSpirit::class, $disableSpy);

        /** @var DisableInsignificantPlayerSpirit $domainAction */
        $domainAction = app(DisableInsignificantPlayerSpirit::class);
        $result = $domainAction->execute($playerSpirit);

        $this->assertTrue($result);
        $disableSpy->shouldHaveReceived('execute');
    }

    public function provides_it_will_disable_player_spirits_with_multiple_games_without_any_stats()
    {
        return [
            Position::QUARTERBACK => [
                'positionName' => Position::QUARTERBACK,
                'previousGamesCount' => 3
            ],
            Position::PITCHER => [
                'positionName' => Position::PITCHER,
                'previousGamesCount' => 4
            ],
            Position::THIRD_BASE => [
                'positionName' => Position::THIRD_BASE,
                'previousGamesCount' => 10
            ],
            Position::POINT_GUARD => [
                'positionName' => Position::POINT_GUARD,
                'previousGamesCount' => 8
            ],
            Position::DEFENSEMAN => [
                'positionName' => Position::DEFENSEMAN,
                'previousGamesCount' => 8
            ],
        ];
    }
}
