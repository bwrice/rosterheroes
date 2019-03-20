<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Players\PlayerDTO;
use App\Domain\Players\PlayerDTOCollection;
use App\Domain\Teams\Team;
use App\Domain\Teams\TeamDTO;
use App\External\Stats\MySportsFeed\LeagueURL;
use App\External\Stats\StatsIntegration;
use App\League;
use App\Positions\Position;
use App\Positions\PositionCollection;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class MySportsFeed implements StatsIntegration
{
    const PASSWORD = 'MYSPORTSFEEDS';
    const BASE_URL = 'https://api.mysportsfeeds.com/v2.0/pull/';
    /**
     * @var Client
     */
    private $guzzleClient;
    /**
     * @var LeagueURL
     */
    private $leagueURL;

    public function __construct(Client $guzzleClient, LeagueURL $leagueURL)
    {
        $this->guzzleClient = $guzzleClient;
        $this->leagueURL = $leagueURL;
    }

    protected function getAPIKey()
    {
        return config('services.mysportsfeed')['key'];
    }


    public function getPlayerDTOs(): Collection
    {
        $playerDTOs = collect();
        League::with('sport')->get()->each(function (League $league) use (&$playerDTOs) {
           $playerDTOs = $playerDTOs->merge($this->getPlayerDTOsForLeague($league));
        });
        return $playerDTOs;
    }

    /**
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getResponse(string $url)
    {
        return $this->guzzleClient->get($url, [
            'auth' => $this->getAuthorization()
        ]);
    }

    protected function getAuthorization()
    {
        return [
            $this->getAPIKey(),
            self::PASSWORD
        ];
    }

    /**
     * @return Collection
     */
    public function getTeamDTOs(): Collection
    {
        $teamDTOs = collect();
        League::all()->each(function (League $league) use (&$teamDTOs){
            $teamDTOs = $teamDTOs->merge($this->getTeamDTOsForLeague($league));
        });
        return $teamDTOs;
    }

    protected function getTeamDTOsForLeague(League $league)
    {
        $fullURL = self::BASE_URL . $this->leagueURL->getTeamsURL($league);
        $response = $this->getResponse($fullURL);
        $data = json_decode($response->getBody(), true);
        $teamDTOs = collect();

        collect($data['teamStatsTotals'])->each(function ($dataArray) use ($league, &$teamDTOs) {
            $teamDTOs = $teamDTOs->push($this->buildTeamDTO($league, $dataArray['team']));
        });

        return $teamDTOs;
    }

    protected function getPlayerDTOsForLeague(League $league)
    {
        $fullURL = self::BASE_URL . $this->leagueURL->getPlayersURL($league);
        $response = $this->getResponse($fullURL);
        $data = json_decode($response->getBody(), true);
        $teams = Team::all();
        /** @var PositionCollection $positions */
        $positions = Position::all();
        $playerDTOs = collect();

        collect($data['players'])->each(function ($dataArray) use ($league, $teams, $positions, &$playerDTOs) {

            if ($dataArray['teamAsOfDate']) {
                $team = $teams->where('external_id', '=', $dataArray['teamAsOfDate']['id'])->first();
            } else {
                $team = null;
            }

            $positionsAbbreviations = $dataArray['player']['alternatePositions'];
            $positionsAbbreviations[] = $dataArray['player']['primaryPosition'];

            $playerPositions = $this->filterPositions($league, $positions, $positionsAbbreviations);

            if ($team) {
                $playerDTOs = $playerDTOs->push($this->buildPlayerDTO($team, $playerPositions, $dataArray['player']));
            }
        });

        return $playerDTOs;
    }

    protected function filterPositions(League $league, PositionCollection $positions, array $posAbbreviations)
    {
        $abbreviations = collect($posAbbreviations)->map(function ($abbreviation) {
            switch($abbreviation) {
                case 'LF':
                case 'RF':
                case 'CF':
                    $abbreviation = 'OF';
            }

            return $abbreviation;
        });

        return $positions->whereIn('abbreviation', $abbreviations)->where('sport_id', '=', $league->sport_id);
    }


    /**
     * @param League $league
     * @param array $teamDataArray
     * @return TeamDTO
     */
    protected function buildTeamDTO(League $league, array $teamDataArray)
    {
        $teamDTO = new TeamDTO(
            $league,
            $teamDataArray['name'],
            $teamDataArray['city'],
            $teamDataArray['abbreviation'],
            $teamDataArray['id']
        );
        return $teamDTO;
    }

    protected function buildPlayerDTO(Team $team, PositionCollection $positions, array $playerDataArray)
    {
        $playerDTO = new PlayerDTO(
            $team,
            $positions,
            $playerDataArray['firstName'],
            $playerDataArray['lastName'],
            $playerDataArray['id']
        );
        return $playerDTO;
    }
}