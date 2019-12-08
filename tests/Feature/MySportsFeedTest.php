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
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\MLBStatNameConverter;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NBAStatNameConverter;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NFLStatNameConverter;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatNameConverters\NHLStatNameConverter;
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
            'teamStatsTotals' => [
                [
                    'team' => [
                            'name' => 'Some Team',
                            'city' => 'Some City',
                            'abbreviation' => 'SCT',
                            'id' => '123abc'
                        ]
                ],
                [
                    'team' =>
                        [
                            'name' => 'Another Team',
                            'city' => 'Another City',
                            'abbreviation' => 'ANT',
                            'id' => '987zyx'
                        ]
                ],
                [
                    'team' => [
                            'name' => 'Last Team',
                            'city' => 'Last City',
                            'abbreviation' => 'LCC',
                            'id' => 'def456'
                        ]
                ],
            ]
        ]);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        // Any league will work since we are mocking the response
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
        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $integrationType = $msfIntegration->getIntegrationType();
        $mlb = League::mlb();

        /** @var Team $teamOne */
        $teamOne = factory(Team::class)->create([
            'league_id' => $mlb->id
        ]);

        $teamOne->externalTeams()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $teamOneExternalID = uniqid()
        ]);

        /** @var Team $teamTwo */
        $teamTwo = factory(Team::class)->create([
            'league_id' => $mlb->id
        ]);

        $teamTwo->externalTeams()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $teamTwoExternalID = uniqid()
        ]);

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'players' => [
                [
                    'teamAsOfDate' => [
                        'id' => $teamOneExternalID,
                    ],
                    'player' => [
                        'firstName' => 'Outfield',
                        'lastName' => 'Man',
                        'id' => '123abc',
                        'primaryPosition' => 'RF',
                        'alternatePositions' => [
                            'LF',
                            'CF'
                        ],
                    ]
                ],
                [
                    'teamAsOfDate' => [
                        'id' => $teamTwoExternalID,
                    ],
                    'player' => [
                        'firstName' => 'Catcher',
                        'lastName' => 'Bro',
                        'id' => '987zyx',
                        'primaryPosition' => 'C',
                        'alternatePositions' => [

                        ],
                    ]

                ],
                [
                    'teamAsOfDate' => [
                        'id' => $teamTwoExternalID,
                    ],
                    'player' => [
                        'firstName' => 'Pitcher',
                        'lastName' => 'Dude',
                        'id' => 'def456',
                        'primaryPosition' => 'P',
                        'alternatePositions' => [

                        ],
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
        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $integrationType = $msfIntegration->getIntegrationType();
        $nba = League::nba();

        /** @var Team $homeTeamOne */
        $homeTeamOne = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        $homeTeamOne->externalTeams()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $homeTeamOneExternalID = uniqid()
        ]);

        /** @var Team $awayTeamOne */
        $awayTeamOne = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        $awayTeamOne->externalTeams()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $awayTeamOneExternalID = uniqid()
        ]);

        /** @var Team $homeTeamTwo */
        $homeTeamTwo = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        $homeTeamTwo->externalTeams()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $homeTeamTwoExternalID = uniqid()
        ]);

        /** @var Team $awayTeamTwo */
        $awayTeamTwo = factory(Team::class)->create([
            'league_id' => $nba->id
        ]);

        $awayTeamTwo->externalTeams()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $awayTeamTwoExternalID = uniqid()
        ]);

        $gameOneID = uniqid();
        $gameTwoID = uniqid();

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn([
            'games' => [
                [
                    'schedule' => [
                        'homeTeam' => [
                            'id' => $homeTeamOneExternalID
                        ],
                        'awayTeam' => [
                            'id' => $awayTeamOneExternalID
                        ],
                        'startTime' => '2019-5-10 16:40:00',
                        'id' => $gameOneID
                    ],
                ],
                [
                    'schedule' => [
                        'homeTeam' => [
                            'id' => $homeTeamTwoExternalID
                        ],
                        'awayTeam' => [
                            'id' => $awayTeamTwoExternalID
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
        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $integrationType = $msfIntegration->getIntegrationType();

        /** @var Team $homeTeam */
        $homeTeam = factory(Team::class)->create([
            'league_id' => $league->id
        ]);

        /** @var Team $awayTeam */
        $awayTeam = factory(Team::class)->create([
            'league_id' => $league->id
        ]);

        $playerOneExternalID = uniqid();
        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create([
            'team_id' => $homeTeam->id,
        ]);

        // Create external player for MSF integration
        $playerOne->externalPlayers()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $playerOneExternalID
        ]);

        $playerTwoExternalID = uniqid();
        // Note: not setting team ID, as it shouldn't matter if a player switches teams
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'team_id' => $awayTeam->id
        ]);

        // Create external player for MSF integration
        $playerTwo->externalPlayers()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $playerTwoExternalID
        ]);

        $gameExternalID = uniqid();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id
        ]);

        // Create external game for MSF integration
        $game->externalGames()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $gameExternalID
        ]);

        $responseArray = [
            'stats' => [
                'away' => [
                    'players' => [
                        [
                            'player' => [
                                'id' => $playerTwoExternalID
                            ],
                            'playerStats' => [
                                // player stats are double nested array from MSF
                                [
                                    'passing' => [
                                        'passYards' => $playerTwoPassYards = 335,
                                        'passTD' => $playerTwoPassTDs = 4,
                                        'passInt' => $playerTwoInts = 1
                                    ],
                                    'rushing' => [
                                        'rushYards' => $playerTwoRushYards = 12,
                                        'rushTD' => 0,
                                    ],
                                    'fumbles' => [
                                        'fumLost' => $playerTwoFumblesLost = 1
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'home' => [
                    'players' => [
                        [
                            'player' => [
                                'id' => $playerOneExternalID
                            ],
                            'playerStats' => [
                                // player stats are double nested array from MSF
                                [
                                    'passing' => [
                                        'passYards' => $playerOnePassYards = 43,
                                        'passTD' => $playerOnePassTDs = 1,
                                        'passInt' => 0
                                    ],
                                    'rushing' => [
                                        'rushYards' => $playerOneRushYards = 85,
                                        'rushTD' => $playerOneRushTDs = 1,
                                    ],
                                    'receiving' => [
                                        'receptions' => $playerOneReceptions = 2,
                                        'recYards' => $playerOneReceivingYards = -7,
                                        'recTD' => $playerOneReceivingTDs = 1
                                    ],
                                    'fumbles' => [
                                        'fumLost' => $playerOneFumblesLost = 1
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn($responseArray);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        // Pull the integration back out of the container
        $msfIntegration = app(MySportsFeed::class);
        $playerGameLogDTOs = $msfIntegration->getPlayerGameLogDTOs($game, 0);

        $this->assertEquals(2, $playerGameLogDTOs->count());
        /** @var PlayerGameLogDTO $playerOneGameLog */
        $playerOneGameLog = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerOne) {
            return $playerGameLogDTO->getPlayer()->id === $playerOne->id;
        });
        $this->assertNotNull($playerOneGameLog);
        $playerOneStatAmountDTOs = $playerOneGameLog->getStatAmountDTOs();
        $statTypeAmounts = [
            StatType::PASS_YARD => $playerOnePassYards,
            StatType::PASS_TD => $playerOnePassTDs,
            StatType::RUSH_YARD => $playerOneRushYards,
            StatType::RUSH_TD => $playerOneRushTDs,
            StatType::RECEPTION => $playerOneReceptions,
            StatType::REC_YARD => $playerOneReceivingYards,
            StatType::REC_TD => $playerOneReceivingTDs,
            StatType::FUMBLE_LOST => $playerOneFumblesLost
        ];
        $this->assertEquals(count($statTypeAmounts), $playerOneStatAmountDTOs->count());
        foreach($statTypeAmounts as $statTypeName => $amount) {
            /** @var StatAmountDTO $matchingStat */
            $matchingStat = $playerOneStatAmountDTOs->first(function (StatAmountDTO $statAmountDTO) use ($statTypeName) {
                return $statAmountDTO->getStatType()->name === $statTypeName;
            });
            $this->assertNotNull($matchingStat);
            $this->assertEquals($amount, $matchingStat->getAmount());
        }

        /** @var PlayerGameLogDTO $playerTwoGameLog */
        $playerTwoGameLog = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwo) {
            return $playerGameLogDTO->getPlayer()->id === $playerTwo->id;
        });
        $this->assertNotNull($playerTwoGameLog);
        $playerTwoStatAmountDTOs = $playerTwoGameLog->getStatAmountDTOs();
        $statTypeAmounts = [
            StatType::PASS_YARD => $playerTwoPassYards,
            StatType::PASS_TD => $playerTwoPassTDs,
            StatType::INTERCEPTION => $playerTwoInts,
            StatType::RUSH_YARD => $playerTwoRushYards,
            StatType::FUMBLE_LOST => $playerTwoFumblesLost
        ];
        $this->assertEquals(count($statTypeAmounts), $playerTwoStatAmountDTOs->count());
        foreach($statTypeAmounts as $statTypeName => $amount) {
            /** @var StatAmountDTO $matchingStat */
            $matchingStat = $playerTwoStatAmountDTOs->first(function (StatAmountDTO $statAmountDTO) use ($statTypeName) {
                return $statAmountDTO->getStatType()->name === $statTypeName;
            });
            $this->assertNotNull($matchingStat);
            $this->assertEquals($amount, $matchingStat->getAmount());
        }
    }

    /**
     * @test
     */
    public function it_will_return_game_log_DTOs_for_NBA()
    {
        $league = League::nba();
        /** @var MySportsFeed $msfIntegration */
        $msfIntegration = app(MySportsFeed::class);
        $integrationType = $msfIntegration->getIntegrationType();

        /** @var Team $homeTeam */
        $homeTeam = factory(Team::class)->create([
            'league_id' => $league->id
        ]);

        /** @var Team $awayTeam */
        $awayTeam = factory(Team::class)->create([
            'league_id' => $league->id
        ]);

        $playerOneExternalID = uniqid();
        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create([
            'team_id' => $homeTeam->id,
        ]);

        // Create external player for MSF integration
        $playerOne->externalPlayers()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $playerOneExternalID
        ]);

        $playerTwoExternalID = uniqid();
        // Note: not setting team ID, as it shouldn't matter if a player switches teams
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'team_id' => $awayTeam->id
        ]);

        // Create external player for MSF integration
        $playerTwo->externalPlayers()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $playerTwoExternalID
        ]);

        $gameExternalID = uniqid();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id
        ]);

        // Create external game for MSF integration
        $game->externalGames()->create([
            'integration_type_id' => $integrationType->id,
            'external_id' => $gameExternalID
        ]);

        $responseArray = [
            'stats' => [
                'away' => [
                    'players' => [
                        [
                            'player' => [
                                'id' => $playerTwoExternalID
                            ],
                            'playerStats' => [
                                // player stats are double nested array from MSF
                                [
                                    'fieldGoals' => [
                                        'fg3PtMade' => $playerTwo3ptMade = 3
                                    ],
                                    'rebounds' => [
                                        'reb' => $playerTwoRebounds = 9
                                    ],
                                    'offense' => [
                                        'ast' => $playerTwoAssists = 6,
                                        'pts' => $playerTwoPoints = 26
                                    ],
                                    'defense' => [
                                        'tov' => $playerTwoTurnovers = 2,
                                        'stl' => $playerTwoSteals = 3,
                                        'blk' => 0
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'home' => [
                    'players' => [
                        [
                            'player' => [
                                'id' => $playerOneExternalID
                            ],
                            'playerStats' => [
                                // player stats are double nested array from MSF
                                [
                                    'fieldGoals' => [
                                        'fg3PtMade' => $playerOne3ptMade = 2
                                    ],
                                    'rebounds' => [
                                        'reb' => $playerOneRebounds = 11
                                    ],
                                    'offense' => [
                                        'ast' => $playerOneAssists = 7,
                                        'pts' => $playerOnePoints = 16
                                    ],
                                    'defense' => [
                                        'tov' => $playerOneTurnovers = 1,
                                        'stl' => $playerOneSteals = 1,
                                        'blk' => $playerOneBlocks = 2
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $clientMock = \Mockery::mock(MSFClient::class);
        $clientMock->shouldReceive('getData')->andReturn($responseArray);

        // put the mock into the container
        app()->instance(MSFClient::class, $clientMock);

        // Pull the integration back out of the container
        $msfIntegration = app(MySportsFeed::class);
        $playerGameLogDTOs = $msfIntegration->getPlayerGameLogDTOs($game, 0);

        $this->assertEquals(2, $playerGameLogDTOs->count());
        /** @var PlayerGameLogDTO $playerOneGameLog */
        $playerOneGameLog = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerOne) {
            return $playerGameLogDTO->getPlayer()->id === $playerOne->id;
        });
        $this->assertNotNull($playerOneGameLog);
        $playerOneStatAmountDTOs = $playerOneGameLog->getStatAmountDTOs();
        $statTypeAmounts = [
            StatType::THREE_POINTER => $playerOne3ptMade,
            StatType::REBOUND => $playerOneRebounds,
            StatType::BASKETBALL_ASSIST => $playerOneAssists,
            StatType::POINT_MADE => $playerOnePoints,
            StatType::STEAL => $playerOneSteals,
            StatType::BASKETBALL_BLOCK => $playerOneBlocks,
            StatType::TURNOVER => $playerOneTurnovers,
        ];
        $this->assertEquals(count($statTypeAmounts), $playerOneStatAmountDTOs->count());
        foreach($statTypeAmounts as $statTypeName => $amount) {
            /** @var StatAmountDTO $matchingStat */
            $matchingStat = $playerOneStatAmountDTOs->first(function (StatAmountDTO $statAmountDTO) use ($statTypeName) {
                return $statAmountDTO->getStatType()->name === $statTypeName;
            });
            $this->assertNotNull($matchingStat);
            $this->assertEquals($amount, $matchingStat->getAmount());
        }

        /** @var PlayerGameLogDTO $playerTwoGameLog */
        $playerTwoGameLog = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwo) {
            return $playerGameLogDTO->getPlayer()->id === $playerTwo->id;
        });
        $this->assertNotNull($playerTwoGameLog);
        $playerTwoStatAmountDTOs = $playerTwoGameLog->getStatAmountDTOs();
        $statTypeAmounts = [
            StatType::THREE_POINTER => $playerTwo3ptMade,
            StatType::REBOUND => $playerTwoRebounds,
            StatType::BASKETBALL_ASSIST => $playerTwoAssists,
            StatType::POINT_MADE => $playerTwoPoints,
            StatType::STEAL => $playerTwoSteals,
            StatType::TURNOVER => $playerTwoTurnovers,
        ];
        $this->assertEquals(count($statTypeAmounts), $playerTwoStatAmountDTOs->count());
        foreach($statTypeAmounts as $statTypeName => $amount) {
            /** @var StatAmountDTO $matchingStat */
            $matchingStat = $playerTwoStatAmountDTOs->first(function (StatAmountDTO $statAmountDTO) use ($statTypeName) {
                return $statAmountDTO->getStatType()->name === $statTypeName;
            });
            $this->assertNotNull($matchingStat);
            $this->assertEquals($amount, $matchingStat->getAmount());
        }
    }

//    /**
//     * @test
//     */
//    public function it_will_return_game_log_DTOs_for_NHL()
//    {
//        $league = League::nhl();
//        $team = uniqid();
//        $teamWeCareAbout = factory(Team::class)->create([
//            'league_id' => $league->id,
//            'external_id' => $team
//        ]);
//
//        $playerOneExternalID = uniqid();
//        /** @var Player $playerOne */
//        $playerOne = factory(Player::class)->create([
//            'team_id' => $teamWeCareAbout->id,
//            'external_id' => $playerOneExternalID
//        ]);
//
//        $playerTwoExternalID = uniqid();
//        // Note: not setting team ID, as it shouldn't matter if a player switches teams
//        /** @var Player $playerTwo */
//        $playerTwo = factory(Player::class)->create([
//            'team_id' => $teamWeCareAbout->id,
//            'external_id' => $playerTwoExternalID
//        ]);
//
//        $gameOneExternalID = uniqid();
//        /** @var Game $gameOne */
//        $gameOne = factory(Game::class)->create([
//            'home_team_id' => $teamWeCareAbout->id,
//            'external_id' => $gameOneExternalID
//        ]);
//
//        $gameTwoExternalID = uniqid();
//        /** @var Game $gameTwo */
//        $gameTwo = factory(Game::class)->create([
//            'away_team_id' => $teamWeCareAbout->id,
//            'external_id' => $gameTwoExternalID
//        ]);
//
//        $gameLogsResponseArray = [
//            [
//                'game' => [
//                    'id' => $gameOneExternalID
//                ],
//                'player' => [
//                    'id' => $playerOneExternalID
//                ],
//                'stats' => [
//                    'scoring' => [
//                        'goals' => 3,
//                        'assists' => 1,
//                        'hatTricks' => 1
//                    ],
//                    'skating' => [
//                        'shots' => 7,
//                        'blockedShots' => 3
//                    ],
//                ]
//            ],
//            [
//                'game' => [
//                    'id' => $gameOneExternalID
//                ],
//                'player' => [
//                    'id' => $playerTwoExternalID
//                ],
//                'stats' => [
//                    'scoring' => [
//                        'goals' => 0,
//                        'assists' => 1,
//                        'hatTricks' => 0
//                    ],
//                    'goaltending' => [
//                        'wins' => 0,
//                        'saves' => 32,
//                        'goalsAgainst' => 4
//                    ],
//                ]
//            ],
//            [
//                'game' => [
//                    'id' => $gameTwoExternalID
//                ],
//                'player' => [
//                    'id' => $playerTwoExternalID
//                ],
//                'stats' => [
//                    'scoring' => [
//                        'goals' => 0,
//                        'assists' => 0,
//                        'hatTricks' => 0
//                    ],
//                    'goaltending' => [
//                        'wins' => 1,
//                        'saves' => 22,
//                        'goalsAgainst' => 0
//                    ],
//                ]
//            ],
//        ];
//
//        $clientMock = \Mockery::mock(MSFClient::class);
//        $clientMock->shouldReceive('getData')->andReturn([
//            'gamelogs' => $gameLogsResponseArray
//        ]);
//
//        // put the mock into the container
//        app()->instance(MSFClient::class, $clientMock);
//
//        /** @var MySportsFeed $msfIntegration */
//        $msfIntegration = app(MySportsFeed::class);
//        $playerGameLogDTOs = $msfIntegration->getHistoricPlayerGameLogDTOs($teamWeCareAbout);
//
//        $this->assertEquals(3, $playerGameLogDTOs->count());
//
//        /** @var NBAStatNameConverter $nhlStatConverter */
//        $nhlStatConverter = app(NHLStatNameConverter::class);
//        $statTypes = StatType::all();
//
//        collect($gameLogsResponseArray)->each(function ($gameLogArray) use ($playerGameLogDTOs, $nhlStatConverter, $statTypes) {
//
//            /** @var PlayerGameLogDTO $playerGameLogDTO */
//            $playerGameLogDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($gameLogArray) {
//                return $playerGameLogDTO->getGame()->external_id === $gameLogArray['game']['id'] &&
//                    $playerGameLogDTO->getPlayer()->external_id === $gameLogArray['player']['id'];
//            });
//
//            $this->assertNotNull($playerGameLogDTO);
//
//            $combinedStats = [];
//            if (isset($gameLogArray['stats']['scoring'])) {
//                $combinedStats = array_merge($gameLogArray['stats']['scoring'], $combinedStats);
//            }
//            if (isset($gameLogArray['stats']['skating'])) {
//                $combinedStats = array_merge($gameLogArray['stats']['skating'], $combinedStats);
//            }
//            if (isset($gameLogArray['stats']['goaltending'])) {
//                $combinedStats = array_merge($gameLogArray['stats']['goaltending'], $combinedStats);
//            }
//
//            collect($combinedStats)->each(function ($amount, $name) use ($playerGameLogDTO, $nhlStatConverter) {
//
//                $convertedName = $nhlStatConverter->convert($name);
//                /** @var StatAmountDTO $statAmountDTO */
//                $statAmountDTO = $playerGameLogDTO->getStatAmountDTOs()->first(function (StatAmountDTO $statAmountDTO) use ($convertedName) {
//                    return $statAmountDTO->getStatType()->name === $convertedName;
//                });
//
//                if ((int) round(abs($amount),2) > 0) {
//                    $this->assertEquals($amount, $statAmountDTO->getAmount());
//                } else {
//                    $this->assertNull($statAmountDTO);
//                }
//            });
//        });
//
//        $playerOneFirstGameDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerOneExternalID, $gameOneExternalID) {
//            return $playerGameLogDTO->getPlayer()->external_id === $playerOneExternalID
//                && $playerGameLogDTO->getGame()->external_id === $gameOneExternalID;
//        });
//        $this->assertNotNull($playerOneFirstGameDTO);
//
//        $playerTwoFirstGameDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwoExternalID, $gameOneExternalID) {
//            return $playerGameLogDTO->getPlayer()->external_id === $playerTwoExternalID
//                && $playerGameLogDTO->getGame()->external_id === $gameOneExternalID;
//        });
//        $this->assertNotNull($playerTwoFirstGameDTO);
//
//        $playerTwoFirstSecondGame = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwoExternalID, $gameTwoExternalID) {
//            return $playerGameLogDTO->getPlayer()->external_id === $playerTwoExternalID
//                && $playerGameLogDTO->getGame()->external_id === $gameTwoExternalID;
//        });
//        $this->assertNotNull($playerTwoFirstSecondGame);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_return_game_log_DTOs_for_MLB()
//    {
//        $league = League::mlb();
//        $team = uniqid();
//        $teamWeCareAbout = factory(Team::class)->create([
//            'league_id' => $league->id,
//            'external_id' => $team
//        ]);
//
//        $playerOneExternalID = uniqid();
//        /** @var Player $playerOne */
//        $playerOne = factory(Player::class)->create([
//            'team_id' => $teamWeCareAbout->id,
//            'external_id' => $playerOneExternalID
//        ]);
//
//        $playerTwoExternalID = uniqid();
//        // Note: not setting team ID, as it shouldn't matter if a player switches teams
//        /** @var Player $playerTwo */
//        $playerTwo = factory(Player::class)->create([
//            'team_id' => $teamWeCareAbout->id,
//            'external_id' => $playerTwoExternalID
//        ]);
//
//        $gameOneExternalID = uniqid();
//        /** @var Game $gameOne */
//        $gameOne = factory(Game::class)->create([
//            'home_team_id' => $teamWeCareAbout->id,
//            'external_id' => $gameOneExternalID
//        ]);
//
//        $gameTwoExternalID = uniqid();
//        /** @var Game $gameTwo */
//        $gameTwo = factory(Game::class)->create([
//            'away_team_id' => $teamWeCareAbout->id,
//            'external_id' => $gameTwoExternalID
//        ]);
//
//        $gameLogsResponseArray = [
//            [
//                'game' => [
//                    'id' => $gameOneExternalID
//                ],
//                'player' => [
//                    'id' => $playerOneExternalID
//                ],
//                'stats' => [
//                    'batting' => [
//                        'runs' => 1,
//                        'hits' => 3,
//                        'secondBaseHits' => 1,
//                        'thirdBaseHits' => 1,
//                        'homeruns' => 1,
//                        'stolenBases' => 2,
//                        'runsBattedIn' => 1,
//                        'batterWalks' => 0,
//                        'hitByPitch' => 1
//                    ],
//                    'pitching' => [
//                        'wins' => 0,
//                        'saves' => 0,
//                        'inningsPitched' => 0,
//                        'pitcherStrikeouts' => 0,
//                        'completedGames' => 0,
//                        'shutouts' => 0,
//                        'battersHit' => 0,
//                        'pitcherWalks' => 0
//                    ],
//                ]
//            ],
//            [
//                'game' => [
//                    'id' => $gameOneExternalID
//                ],
//                'player' => [
//                    'id' => $playerTwoExternalID
//                ],
//                'stats' => [
//                    'batting' => [
//                        'runs' => 0,
//                        'hits' => 1,
//                        'secondBaseHits' => 0,
//                        'thirdBaseHits' => 0,
//                        'homeruns' => 0,
//                        'stolenBases' => 0,
//                        'runsBattedIn' => 0,
//                        'batterWalks' => 1,
//                        'hitByPitch' => 0
//                    ],
//                    'pitching' => [
//                        'wins' => 0,
//                        'saves' => 1,
//                        'inningsPitched' => 2.2,
//                        'pitcherStrikeouts' => 3,
//                        'completedGames' => 0,
//                        'shutouts' => 0,
//                        'battersHit' => 1,
//                        'pitcherWalks' => 1
//                    ],
//                ]
//            ],
//            [
//                'game' => [
//                    'id' => $gameTwoExternalID
//                ],
//                'player' => [
//                    'id' => $playerTwoExternalID
//                ],
//                'stats' => [
//                    'batting' => [
//                        'runs' => 0,
//                        'hits' => 0,
//                        'secondBaseHits' => 0,
//                        'thirdBaseHits' => 0,
//                        'homeruns' => 0,
//                        'stolenBases' => 0,
//                        'runsBattedIn' => 0,
//                        'batterWalks' => 0,
//                        'hitByPitch' => 0
//                    ],
//                    'pitching' => [
//                        'wins' => 1,
//                        'saves' => 0,
//                        'inningsPitched' => 9,
//                        'pitcherStrikeouts' => 11,
//                        'completedGames' => 1,
//                        'shutouts' => 1,
//                        'battersHit' => 0,
//                        'pitcherWalks' => 0
//                    ],
//                ]
//            ],
//        ];
//
//        $clientMock = \Mockery::mock(MSFClient::class);
//        $clientMock->shouldReceive('getData')->andReturn([
//            'gamelogs' => $gameLogsResponseArray
//        ]);
//
//        // put the mock into the container
//        app()->instance(MSFClient::class, $clientMock);
//
//        /** @var MySportsFeed $msfIntegration */
//        $msfIntegration = app(MySportsFeed::class);
//        $playerGameLogDTOs = $msfIntegration->getHistoricPlayerGameLogDTOs($teamWeCareAbout);
//
//        $this->assertEquals(3, $playerGameLogDTOs->count());
//
//        /** @var NBAStatNameConverter $nhlStatConverter */
//        $nhlStatConverter = app(MLBStatNameConverter::class);
//        $statTypes = StatType::all();
//
//        collect($gameLogsResponseArray)->each(function ($gameLogArray) use ($playerGameLogDTOs, $nhlStatConverter, $statTypes) {
//
//            /** @var PlayerGameLogDTO $playerGameLogDTO */
//            $playerGameLogDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($gameLogArray) {
//                return $playerGameLogDTO->getGame()->external_id === $gameLogArray['game']['id'] &&
//                    $playerGameLogDTO->getPlayer()->external_id === $gameLogArray['player']['id'];
//            });
//
//            $this->assertNotNull($playerGameLogDTO);
//
//            $combinedStats = [];
//            if (isset($gameLogArray['stats']['batting'])) {
//                $combinedStats = array_merge($gameLogArray['stats']['batting'], $combinedStats);
//            }
//            if (isset($gameLogArray['stats']['pitching'])) {
//                $combinedStats = array_merge($gameLogArray['stats']['pitching'], $combinedStats);
//            }
//
//            collect($combinedStats)->each(function ($amount, $name) use ($playerGameLogDTO, $nhlStatConverter) {
//
//                $convertedName = $nhlStatConverter->convert($name);
//                /** @var StatAmountDTO $statAmountDTO */
//                $statAmountDTO = $playerGameLogDTO->getStatAmountDTOs()->first(function (StatAmountDTO $statAmountDTO) use ($convertedName) {
//                    return $statAmountDTO->getStatType()->name === $convertedName;
//                });
//
//                if ((int) round(abs($amount),2) > 0) {
//                    $this->assertEquals($amount, $statAmountDTO->getAmount());
//                } else {
//                    $this->assertNull($statAmountDTO);
//                }
//            });
//        });
//
//        $playerOneFirstGameDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerOneExternalID, $gameOneExternalID) {
//            return $playerGameLogDTO->getPlayer()->external_id === $playerOneExternalID
//                && $playerGameLogDTO->getGame()->external_id === $gameOneExternalID;
//        });
//        $this->assertNotNull($playerOneFirstGameDTO);
//
//        $playerTwoFirstGameDTO = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwoExternalID, $gameOneExternalID) {
//            return $playerGameLogDTO->getPlayer()->external_id === $playerTwoExternalID
//                && $playerGameLogDTO->getGame()->external_id === $gameOneExternalID;
//        });
//        $this->assertNotNull($playerTwoFirstGameDTO);
//
//        $playerTwoFirstSecondGame = $playerGameLogDTOs->first(function (PlayerGameLogDTO $playerGameLogDTO) use ($playerTwoExternalID, $gameTwoExternalID) {
//            return $playerGameLogDTO->getPlayer()->external_id === $playerTwoExternalID
//                && $playerGameLogDTO->getGame()->external_id === $gameTwoExternalID;
//        });
//        $this->assertNotNull($playerTwoFirstSecondGame);
//    }
}
