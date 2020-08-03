<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/19/19
 * Time: 8:29 AM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\ExternalGame;

class BoxScoreAPI
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

    public function getData(Game $game, int $integrationTypeID, int $yearDelta = 0)
    {
        /** @var ExternalGame $externalGame */
        $externalGame = $game->externalGames()->where('integration_type_id', '=', $integrationTypeID)->firstOrFail();
        $league = $game->homeTeam->league;

        $queryArgs['playerStats'] = $this->statTypeArgs($league);
        $queryArgs['teamStats'] = 'none';

        $regularSeason = $game->season_type === Game::SEASON_TYPE_REGULAR;
        $season = $this->leagueSeasonConverter->getSeason($league, $yearDelta, $regularSeason);

        $subURL = strtolower($league->abbreviation) . '/'. $season;
        $subURL .= $regularSeason ? '-regular' : '-playoff';
        $subURL .= '/games/' . $externalGame->external_id . '/boxscore.json';

        $responseData = $this->client->getData($subURL, $queryArgs);
        return $responseData;
    }

    protected function statTypeArgs(League $league)
    {
        $args = [];
        switch ($league->abbreviation) {
            case League::NFL:
                $args = [
                    'passYards',
                    'passTD',
                    'passInt',
                    'rushYards',
                    'rushTD',
                    'recYards',
                    'recTD',
                    'receptions',
                    'fumLost',
                ];
                break;
            case League::MLB:
                $args = [
                    'runs',
                    'hits',
                    'secondBaseHits',
                    'thirdBaseHits',
                    'homeruns',
                    'stolenBases',
                    'runsBattedIn',
                    'batterWalks',
                    'hitByPitch',
                    'wins',
                    'saves',
                    'inningsPitched',
                    'pitcherStrikeouts',
                    'hitsAllowed',
                    'earnedRunsAllowed',
                    'completedGames',
                    'shutouts',
                    'battersHit',
                    'pitcherWalks',
                ];
                break;
            case League::NBA:
                $args = [
                    'fg3PtMade',
                    'reb',
                    'ast',
                    'pts',
                    'tov',
                    'stl',
                    'blk',
                ];
                break;
            case League::NHL:
                $args = [
                    'goals',
                    'assists',
                    'hatTricks',
                    'shots',
                    'blockedShots',
                    'wins',
                    'saves',
                    'goalsAgainst',
                ];
                break;
        }
        return implode(',', $args);
    }
}
