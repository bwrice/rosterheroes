<?php

namespace Tests\Feature;

use App\Domain\Actions\DisableInsignificantPlayerSpirit;
use App\Domain\Actions\DisablePlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\PlayerStatFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

// TODO: Delete?
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
                'previousGamesCount' => 4
            ],
            Position::PITCHER => [
                'positionName' => Position::PITCHER,
                'previousGamesCount' => 16
            ],
            Position::THIRD_BASE => [
                'positionName' => Position::THIRD_BASE,
                'previousGamesCount' => 6
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

    /**
     * @test
     * @dataProvider provides_it_will_not_disable_player_spirits_with_only_a_few_games_without_stats
     * @param $positionName
     * @param $previousGamesCount
     */
    public function it_will_not_disable_player_spirits_with_only_a_few_games_without_stats($positionName, $previousGamesCount)
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

        $this->assertFalse($result);
        $disableSpy->shouldNotHaveReceived('execute');
    }

    public function provides_it_will_not_disable_player_spirits_with_only_a_few_games_without_stats()
    {
        return [
            Position::QUARTERBACK => [
                'positionName' => Position::QUARTERBACK,
                'previousGamesCount' => 2
            ],
            Position::PITCHER => [
                'positionName' => Position::PITCHER,
                'previousGamesCount' => 8
            ],
            Position::THIRD_BASE => [
                'positionName' => Position::THIRD_BASE,
                'previousGamesCount' => 4
            ],
            Position::POINT_GUARD => [
                'positionName' => Position::POINT_GUARD,
                'previousGamesCount' => 4
            ],
            Position::DEFENSEMAN => [
                'positionName' => Position::DEFENSEMAN,
                'previousGamesCount' => 4
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_it_will_disable_player_spirits_if_the_player_has_a_majority_of_insignificant_games
     * @param $positionName
     * @param $insignificantGamesCount
     * @param $significantGamesCount
     * @param $statType
     * @param $insignificantAmount
     * @param $significantAmount
     */
    public function it_will_disable_player_spirits_if_the_player_has_a_majority_of_insignificant_games(
        $positionName,
        $insignificantGamesCount,
        $significantGamesCount,
        $statType,
        $insignificantAmount,
        $significantAmount)
    {
        $game = GameFactory::new()->create([
            'starts_at' => Date::now()->addDays(5)
        ]);
        $player = PlayerFactory::new()->withPosition(Position::forName($positionName))->create();
        $baseGameLogFactory = PlayerGameLogFactory::new()->forPlayer($player);
        $playerSpiritsGameLog = $baseGameLogFactory->forGame($game);
        $playerSpirit = PlayerSpiritFactory::new()->withPlayerGameLog($playerSpiritsGameLog)->create();


        $playerStatFactory = PlayerStatFactory::new()->forStatType($statType);
        $insignificantStatsFactory = $playerStatFactory->withAmount($insignificantAmount);
        /*
         * Make older game logs with insignificant stats
         */
        foreach (range(1, $insignificantGamesCount) as $count) {
            $game = GameFactory::new()->create([
                'starts_at' => Date::now()->subDays($count)
            ]);
            $baseGameLogFactory->forGame($game)->withStats(collect([$insignificantStatsFactory]))->create();
        }

        /*
         * Make older game logs with significant stats
         */
        $significantStatsFactory = $playerStatFactory->withAmount($significantAmount);
        foreach (range(1, $significantGamesCount) as $count) {
            $game = GameFactory::new()->create([
                'starts_at' => Date::now()->subDays($count + 1)
            ]);
            $baseGameLogFactory->forGame($game)->withStats(collect([$significantStatsFactory]))->create();
        }

        // verify we have previous game logs and the one for the created player spirit
        $expectedLogsCount = $insignificantGamesCount + $significantGamesCount + 1;
        $this->assertEquals($expectedLogsCount, $player->playerGameLogs()->count());

        $disableSpy = \Mockery::spy(DisablePlayerSpirit::class);
        app()->instance(DisablePlayerSpirit::class, $disableSpy);

        /** @var DisableInsignificantPlayerSpirit $domainAction */
        $domainAction = app(DisableInsignificantPlayerSpirit::class);
        $result = $domainAction->execute($playerSpirit);

        $this->assertTrue($result);
        $disableSpy->shouldHaveReceived('execute');
    }


    public function provides_it_will_disable_player_spirits_if_the_player_has_a_majority_of_insignificant_games()
    {
        return [
            Position::QUARTERBACK => [
                'positionName' => Position::QUARTERBACK,
                'insignificantGamesCount' => 10,
                'significantGamesCount' => 2,
                'statType' => StatType::PASS_YARD,
                'insignificantAmount' => 15,
                'significantAmount' => 100
            ],
            Position::PITCHER => [
                'positionName' => Position::PITCHER,
                'insignificantGamesCount' => 14,
                'significantGamesCount' => 3,
                'statType' => StatType::INNING_PITCHED,
                'insignificantAmount' => .1,
                'significantAmount' => 2
            ],
            Position::THIRD_BASE => [
                'positionName' => Position::THIRD_BASE,
                'insignificantGamesCount' => 10,
                'significantGamesCount' => 2,
                'statType' => StatType::BASE_ON_BALLS,
                'insignificantAmount' => 1,
                'significantAmount' => 3
            ],
            Position::POINT_GUARD => [
                'positionName' => Position::POINT_GUARD,
                'insignificantGamesCount' => 10,
                'significantGamesCount' => 2,
                'statType' => StatType::PASS_YARD,
                'insignificantAmount' => 15,
                'significantAmount' => 100
            ],
            Position::DEFENSEMAN => [
                'positionName' => Position::DEFENSEMAN,
                'insignificantGamesCount' => 10,
                'significantGamesCount' => 2,
                'statType' => StatType::PASS_YARD,
                'insignificantAmount' => 15,
                'significantAmount' => 100
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provides_it_will_NOT_disable_player_spirits_if_the_player_has_a_only_a_few_insignificant_games
     * @param $positionName
     * @param $insignificantGamesCount
     * @param $significantGamesCount
     * @param $statType
     * @param $insignificantAmount
     * @param $significantAmount
     */
    public function it_will_NOT_disable_player_spirits_if_the_player_has_a_only_a_few_insignificant_games(
        $positionName,
        $insignificantGamesCount,
        $significantGamesCount,
        $statType,
        $insignificantAmount,
        $significantAmount)
    {
        $game = GameFactory::new()->create([
            'starts_at' => Date::now()->addDays(5)
        ]);
        $player = PlayerFactory::new()->withPosition(Position::forName($positionName))->create();
        $baseGameLogFactory = PlayerGameLogFactory::new()->forPlayer($player);
        $playerSpiritsGameLog = $baseGameLogFactory->forGame($game);
        $playerSpirit = PlayerSpiritFactory::new()->withPlayerGameLog($playerSpiritsGameLog)->create();


        $playerStatFactory = PlayerStatFactory::new()->forStatType($statType);
        $insignificantStatsFactory = $playerStatFactory->withAmount($insignificantAmount);
        /*
         * Make older game logs with insignificant stats
         */
        foreach (range(1, $insignificantGamesCount) as $count) {
            $game = GameFactory::new()->create([
                'starts_at' => Date::now()->subDays($count)
            ]);
            $baseGameLogFactory->forGame($game)->withStats(collect([$insignificantStatsFactory]))->create();
        }

        /*
         * Make older game logs with significant stats
         */
        $significantStatsFactory = $playerStatFactory->withAmount($significantAmount);
        foreach (range(1, $significantGamesCount) as $count) {
            $game = GameFactory::new()->create([
                'starts_at' => Date::now()->subDays($count + 1)
            ]);
            $baseGameLogFactory->forGame($game)->withStats(collect([$significantStatsFactory]))->create();
        }

        // verify we have previous game logs and the one for the created player spirit
        $expectedLogsCount = $insignificantGamesCount + $significantGamesCount + 1;
        $this->assertEquals($expectedLogsCount, $player->playerGameLogs()->count());

        $disableSpy = \Mockery::spy(DisablePlayerSpirit::class);
        app()->instance(DisablePlayerSpirit::class, $disableSpy);

        /** @var DisableInsignificantPlayerSpirit $domainAction */
        $domainAction = app(DisableInsignificantPlayerSpirit::class);
        $result = $domainAction->execute($playerSpirit);

        $this->assertFalse($result);
        $disableSpy->shouldNotHaveReceived('execute');
    }


    public function provides_it_will_NOT_disable_player_spirits_if_the_player_has_a_only_a_few_insignificant_games()
    {
        return [
            Position::QUARTERBACK => [
                'positionName' => Position::QUARTERBACK,
                'insignificantGamesCount' => 4,
                'significantGamesCount' => 4,
                'statType' => StatType::PASS_YARD,
                'insignificantAmount' => 15,
                'significantAmount' => 100
            ],
            Position::PITCHER => [
                'positionName' => Position::PITCHER,
                'insignificantGamesCount' => 16,
                'significantGamesCount' => 8,
                'statType' => StatType::INNING_PITCHED,
                'insignificantAmount' => .1,
                'significantAmount' => 2
            ],
            Position::THIRD_BASE => [
                'positionName' => Position::THIRD_BASE,
                'insignificantGamesCount' => 8,
                'significantGamesCount' => 5,
                'statType' => StatType::BASE_ON_BALLS,
                'insignificantAmount' => 1,
                'significantAmount' => 3
            ],
            Position::POINT_GUARD => [
                'positionName' => Position::POINT_GUARD,
                'insignificantGamesCount' => 8,
                'significantGamesCount' => 5,
                'statType' => StatType::PASS_YARD,
                'insignificantAmount' => 15,
                'significantAmount' => 100
            ],
            Position::DEFENSEMAN => [
                'positionName' => Position::DEFENSEMAN,
                'insignificantGamesCount' => 8,
                'significantGamesCount' => 5,
                'statType' => StatType::PASS_YARD,
                'insignificantAmount' => 15,
                'significantAmount' => 100
            ],
        ];
    }
}
