<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\DataTransferObjects\TeamDTO;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
use App\Domain\Models\Team;
use App\External\Stats\MySportsFeed\MSFClient;
use App\External\Stats\MySportsFeed\MySportsFeed;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NFLStatNameConverter;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MySportsFeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_valid_team_dtos()
    {
        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'teamStatsTotal' => [
                [
                    'name' => 'Some Team',
                    'city' => 'Some City',
                    'abbreviation' => 'SCT',
                    'id' => '123abc'
                ],
                [
                    'name' => 'Another Team',
                    'city' => 'Another City',
                    'abbreviation' => 'ANT',
                    'id' => '987zyx'
                ],
                [
                    'name' => 'Last Team',
                    'city' => 'Last City',
                    'abbreviation' => 'LCC',
                    'id' => 'def456'
                ]
            ]
        ]);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        $league = League::first();

        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $teamDTOs = $msfIntegration->getTeamDTOs($league);

        $this->assertEquals(3, $teamDTOs->count());

        $teamDTOs->each(function (TeamDTO $teamDTO) use($league) {
            $this->assertEquals($league->id, $teamDTO->getLeague()->id);
        });
    }

    /**
     * @test
     */
    public function it_will_return_valid_player_dtos()
    {

        $mlb = League::mlb();

        /** @var Team $teamOne */
        $teamOne = factory(Team::class)->create([
            'league_id' => $mlb->id
        ]);

        /** @var Team $teamTwo */
        $teamTwo = factory(Team::class)->create([
            'league_id' => $mlb->id
        ]);

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'players' => [
                [
                    'firstName' => 'Outfield',
                    'lastName' => 'Man',
                    'teamAsOfDate' => [
                        'id' => $teamOne->external_id,
                    ],
                    'id' => '123abc',
                    'primaryPosition' => 'RF',
                    'alternatePositions' => [
                        'LF',
                        'CF'
                    ]
                ],
                [
                    'firstName' => 'Catcher',
                    'lastName' => 'Bro',
                    'teamAsOfDate' => [
                        'id' => $teamOne->external_id,
                    ],
                    'id' => '987zyx',
                    'primaryPosition' => 'C',
                    'alternatePositions' => [

                    ]
                ],
                [
                    'firstName' => 'Pitcher',
                    'lastName' => 'Dude',
                    'teamAsOfDate' => [
                        'id' => $teamTwo->external_id,
                    ],
                    'id' => 'def456',
                    'primaryPosition' => 'P',
                    'alternatePositions' => [

                    ]
                ]
            ]
        ]);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $playerDTOs = $msfIntegration->getPlayerDTOs($mlb);

        $this->assertEquals(3, $playerDTOs->count(), "Correct amount of DTOs");

        /** @var PlayerDTO $outfieldPlayer */
        $outfieldPlayer = $playerDTOs->first(function (PlayerDTO $playerDTO) {
            return $playerDTO->getFirstName() === 'Outfield';
        });
        $outfieldPosition = Position::where('name', 'Outfield')->first();
        $this->assertEquals($outfieldPosition->id, $outfieldPlayer->getPositions()->first()->id, "Position got converted correctly");
    }

    /**
     * @test
     */
    public function it_will_return_game_DTOs()
    {
        $nba = League::nba();

        /** @var Team $homeTeamOne */
        $homeTeamOne = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        /** @var Team $awayTeamOne */
        $awayTeamOne = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        /** @var Team $homeTeamTwo */
        $homeTeamTwo = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        /** @var Team $awayTeamTwo */
        $awayTeamTwo = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        $gameOneID = '123abc';
        $gameTwoID = '456def';

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'games' => [
                [
                    'schedule' => [
                        'homeTeam' => [
                            'id' => $homeTeamOne->external_id
                        ],
                        'awayTeam' => [
                            'id' => $awayTeamOne->external_id
                        ],
                        'startTime' => '2019-5-10 16:40:00',
                        'id' => $gameOneID
                    ],
                ],
                [
                    'schedule' => [
                        'homeTeam' => [
                            'id' => $homeTeamTwo->external_id
                        ],
                        'awayTeam' => [
                            'id' => $awayTeamTwo->external_id
                        ],
                        'startTime' => '2019-5-12 13:15:00',
                        'id' => $gameTwoID
                    ],
                ]
            ]
        ]);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);


        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $gameDTOs = $msfIntegration->getGameDTOs($nba);
        $this->assertEquals(2, $gameDTOs->count(), "Correct amount of game DTOs");

        /** @var GameDTO $gameOne */
        $gameOne = $gameDTOs->first(function (GameDTO $gameDTO) use ($gameOneID) {
            return $gameDTO->getExternalID() === $gameOneID;
        });
        $this->assertEquals($homeTeamOne->id, $gameOne->getHomeTeam()->id);
        $this->assertEquals($awayTeamOne->id, $gameOne->getAwayTeam()->id);

        /** @var GameDTO $gameTwo */
        $gameTwo = $gameDTOs->first(function (GameDTO $gameDTO) use ($gameTwoID) {
            return $gameDTO->getExternalID() === $gameTwoID;
        });
        $this->assertEquals($homeTeamTwo->id, $gameTwo->getHomeTeam()->id);
        $this->assertEquals($awayTeamTwo->id, $gameTwo->getAwayTeam()->id);

    }

    /**
     * @test
     */
    public function it_will_return_game_log_DTOs_for_NFL()
    {
        $league = League::nfl();
        $team = uniqid();
        $teamWeCareAbout = factory(Team::class)->create([
            'league_id' => $league->id,
            'external_id' => $team
        ]);

        $playerOneExternalID = uniqid();
        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create([
            'team_id' => $teamWeCareAbout->id,
            'external_id' => $playerOneExternalID
        ]);

        $playerTwoExternalID = uniqid();
        // Note: not setting team ID, as it shouldn't matter if a player switches teams
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'external_id' => $playerTwoExternalID
        ]);

        $gameOneExternalID = uniqid();
        /** @var Game $gameOne */
        $gameOne = factory(Game::class)->create([
            'home_team_id' => $teamWeCareAbout->id,
            'external_id' => $gameOneExternalID
        ]);

        $gameTwoExternalID = uniqid();
        /** @var Game $gameTwo */
        $gameTwo = factory(Game::class)->create([
            'away_team_id' => $teamWeCareAbout->id,
            'external_id' => $gameTwoExternalID
        ]);

        $gameLogsResponseArray = [
            [
                'game' => [
                    'id' => $gameOneExternalID
                ],
                'player' => [
                    'id' => $playerOneExternalID
                ],
                'stats' => [
                    'passing' => [
                        'passYards' => 335,
                        'passTD' => 4,
                        'passInt' => 1
                    ],
                    'rushing' => [
                        'rushYards' => 12,
                        'rushTD' => 0,
                    ],
                    'receiving' => [
                        'receptions' => 0,
                        'recYards' => 0,
                        'recTD' => 0

                    ],
                    'fumbles' => [
                        'fumLost' => 1
                    ]
                ]
            ],
            [
                'game' => [
                    'id' => $gameOneExternalID
                ],
                'player' => [
                    'id' => $playerTwoExternalID
                ],
                'stats' => [
                    'passing' => [
                        'passYards' => 0,
                        'passTD' => 0,
                        'passInt' => 0
                    ],
                    'rushing' => [
                        'rushYards' => 85,
                        'rushTD' => 1,
                    ],
                    'receiving' => [
                        'receptions' => 2,
                        'recYards' => -7,
                        'recTD' => 0

                    ],
                    'fumbles' => [
                        'fumLost' => 1
                    ]
                ]
            ],
            [
                'game' => [
                    'id' => $gameTwoExternalID
                ],
                'player' => [
                    'id' => $playerTwoExternalID
                ],
                'stats' => [
                    'passing' => [
                        'passYards' => 0,
                        'passTD' => 0,
                        'passInt' => 0
                    ],
                    'rushing' => [
                        'rushYards' => 43,
                        'rushTD' => 0,
                    ],
                    'receiving' => [
                        'receptions' => 5,
                        'recYards' => 77,
                        'recTD' => 2

                    ],
                    'fumbles' => [
                        'fumLost' => 0
                    ]
                ]
            ],
        ];

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'gamelogs' => $gameLogsResponseArray
        ]);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $playerGameLogDTOs = $msfIntegration->getPlayerGameLogDTOs($teamWeCareAbout);

        $this->assertEquals(3, $playerGameLogDTOs->count());

        /** @var NFLStatNameConverter $nflStatNameConverter */
        $nflStatNameConverter = app(NFLStatNameConverter::class);
        $statTypes = StatType::all();

        collect($gameLogsResponseArray)->each(function ($gameLogArray) use ($playerGameLogDTOs, $nflStatNameConverter, $statTypes) {

            /** @var PlayerGameLogDTO $playerGameLogDTO */
            $playerGameLogDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($gameLogArray) {
                return $playerGameLogDTO->getGame()->external_id === $gameLogArray['game']['id'] &&
                    $playerGameLogDTO->getPlayer()->external_id === $gameLogArray['player']['id'];
            });

            $this->assertNotNull($playerGameLogDTO);

            $combinedStats = $gameLogArray['stats']['passing'];
            $combinedStats = array_merge($gameLogArray['stats']['rushing'], $combinedStats);
            $combinedStats = array_merge($gameLogArray['stats']['receiving'], $combinedStats);
            $combinedStats = array_merge($gameLogArray['stats']['fumbles'], $combinedStats);

            collect($combinedStats)->each(function ($amount, $name) use ($playerGameLogDTO, $nflStatNameConverter) {

                $convertedName = $nflStatNameConverter->convert($name);
                /** @var StatAmountDTO $statAmountDTO */
                $statAmountDTO = $playerGameLogDTO->getStatAmountDTOs()->first(function (StatAmountDTO $statAmountDTO) use ($convertedName) {
                    return $statAmountDTO->getStatType()->name === $convertedName;
                });

                if ((int) round(abs($amount),2) > 0) {
                    $this->assertEquals($amount, $statAmountDTO->getAmount());
                } else {
                    $this->assertNull($statAmountDTO);
                }
            });
        });

        $playerOneFirstGameDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerOneExternalID, $gameOneExternalID) {
            return $playerGameLogDTO->getPlayer()->external_id === $playerOneExternalID
                && $playerGameLogDTO->getGame()->external_id === $gameOneExternalID;
        });
        $this->assertNotNull($playerOneFirstGameDTO);

        $playerTwoFirstGameDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwoExternalID, $gameOneExternalID) {
            return $playerGameLogDTO->getPlayer()->external_id === $playerTwoExternalID
                && $playerGameLogDTO->getGame()->external_id === $gameOneExternalID;
        });
        $this->assertNotNull($playerTwoFirstGameDTO);

        $playerTwoFirstSecondGame = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwoExternalID, $gameTwoExternalID) {
            return $playerGameLogDTO->getPlayer()->external_id === $playerTwoExternalID
                && $playerGameLogDTO->getGame()->external_id === $gameTwoExternalID;
        });
        $this->assertNotNull($playerTwoFirstSecondGame);
    }

    /**
     * @test
     */
    public function it_will_return_game_log_DTOs_for_NBA()
    {

    }

    /**
     * @test
     */
    public function it_will_return_game_log_DTOs_for_NHL()
    {

    }

    /**
     * @test
     */
    public function it_will_return_game_log_DTOs_for_MLB()
    {

    }
}
