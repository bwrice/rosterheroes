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
    /**
     * @var PositionConverter
     */
    private $positionConverter;

    public function __construct(MSFClient $client, LeagueSeasonConverter $leagueSeasonConverter, PositionConverter $positionConverter)
    {
        $this->client = $client;
        $this->leagueSeasonConverter = $leagueSeasonConverter;
        $this->positionConverter = $positionConverter;
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
            return $this->positionConverter->convertPositionNameIntoAbbreviations($position->name);
        })->flatten();
    }
}
