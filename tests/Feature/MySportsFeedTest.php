<?php

namespace Tests\Feature;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\Domain\DataTransferObjects\TeamDTO;
use App\Domain\Models\League;
use App\Domain\Models\Position;
use App\Domain\Models\Team;
use App\External\Stats\MySportsFeed\MSFClient;
use App\External\Stats\MySportsFeed\MySportsFeed;
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
}
