<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/19/19
 * Time: 8:29 AM
 */

namespace App\External\Stats\MySportsFeed;


use App\Domain\Collections\PositionCollection;
use App\Domain\Models\League;
use App\Domain\Models\Position;
use App\Domain\Models\Team;

class GameLogAPI
{
    /**
     * @var MSFClient
     */
    private $client;
    /**
     * @var LeagueSeasonConverter
     */
    private $leagueSeasonConverter;

    public function __construct(MSFClient $client, LeagueSeasonConverter $leagueSeasonConverter)
    {
        $this->client = $client;
        $this->leagueSeasonConverter = $leagueSeasonConverter;
    }

    public function getData(Team $team, PositionCollection $positions, int $yearDelta = 0)
    {
        $positionArgs = $this->convertPositions($positions)->implode(',');
        $season = $this->leagueSeasonConverter->getSeason($team->league, $yearDelta);
        $subURL = strtolower($team->league->abbreviation) . '/'. $season . '-regular/player_gamelogs.json?team=' . strtolower($team->abbreviation);
        $responseData = $this->client->getData($subURL);
        return $responseData['gamelogs'];
    }

    protected function convertPositions(PositionCollection $positions)
    {
        return $positions->map(function (Position $position) {
            switch($position->name) {
                case Position::QUARTERBACK:
                    return 'QB';
                case Position::RUNNING_BACK:
                    return 'RB';
                case Position::WIDE_RECEIVER:
                    return 'WR';
                case Position::TIGHT_END:
                    return 'TE';
                case Position::FIRST_BASE:
                    return '1B';
                case Position::SECOND_BASE:
                    return '2B';
                case Position::THIRD_BASE:
                    return '3B';
                case Position::SHORTSTOP:
                    return 'SS';
                case Position::PITCHER:
                    return 'P';
                case Position::OUTFIELD:
                    return ['LF', 'RF', 'CF'];
                case Position::POINT_GUARD:
                    return 'PG';
                case Position::SHOOTING_GUARD:
                    return 'SG';
                case Position::SMALL_FORWARD:
                    return 'SF';
                case Position::POWER_FORWARD:
                    return 'PF';
                case Position::LEFT_WING:
                    return 'LW';
                case Position::RIGHT_WING:
                    return 'RW';
                case Position::DEFENSEMAN:
                    return 'D';
                case Position::GOALIE:
                    return 'G';
                case Position::CATCHER:
                case Position::HOCKEY_CENTER:
                case Position::BASKETBALL_CENTER:
                    return 'C';
                default:
                    return '';
            }
        })->flatten();
    }
}
