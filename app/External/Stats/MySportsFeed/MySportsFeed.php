<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Players\PlayerDTO;
use App\Domain\Teams\Team;
use App\Domain\Teams\TeamDTO;
use App\External\Stats\StatsIntegration;
use App\League;
use App\Positions\Position;
use App\Positions\PositionCollection;
use Illuminate\Support\Collection;

class MySportsFeed implements StatsIntegration
{
    /**
     * @var PlayerAPI
     */
    private $playerAPI;
    /**
     * @var TeamAPI
     */
    private $teamAPI;
    /**
     * @var GameAPI
     */
    private $gameAPI;

    public function __construct(PlayerAPI $playerAPI, TeamAPI $teamAPI, GameAPI $gameAPI)
    {
        $this->playerAPI = $playerAPI;
        $this->teamAPI = $teamAPI;
        $this->gameAPI = $gameAPI;
    }

    protected function getAPIKey()
    {
        return config('services.mysportsfeed')['key'];
    }

    public function getPlayerDTOs(): Collection
    {
        $playerDTOs = collect();
        $data = $this->playerAPI->getData();
        $teams = Team::with('league')->get();
        /** @var PositionCollection $positions */
        $positions = Position::all();

        foreach($data as $playerArray) {
            /** @var Team $team */
            $team = $playerArray['teamAsOfDate'] ? $teams->where('external_id', '=', $playerArray['teamAsOfDate']['id'])->first() : null;

            if ($team) {
                $player = $playerArray['player'];
                $positionsAbbreviations = $player['alternatePositions'];
                $positionsAbbreviations[] = $player['primaryPosition'];
                $playerPositions = $this->filterPositions($team->league, $positions, $positionsAbbreviations);

                if ($playerPositions->isNotEmpty()) {
                    $playerDTOs->push(new PlayerDTO(
                        $team,
                        $playerPositions,
                        $player['firstName'],
                        $player['lastName'],
                        $player['id']
                    ));
                }
            }
        }
        return $playerDTOs;
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

    protected function filterPositions(League $league, PositionCollection $positions, array $posAbbreviations)
    {
        $abbreviations = collect($posAbbreviations)->map(function ($abbreviation) {
            // We only use outfield (OF) for all outfield positions
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
     * @return Collection
     */
    public function getTeamDTOs(): Collection
    {
        $teamDTOs = collect();
        $data = $this->teamAPI->getData();
        $leagues = League::all();

        foreach($data as $leagueAbv => $teamsData) {

            $league = $leagues->where('abbreviation', '=', $leagueAbv)->first();
            if (! $league) {
                throw new \RuntimeException("Couldn't create team DTOs because league with abbreviation: " . $leagueAbv . " not found");
            }
            /** @var League $league */
            foreach($teamsData as $teamArray) {
                $teamData = $teamArray['team'];
                $teamDTOs->push(new TeamDTO(
                    $league,
                    $teamData['name'],
                    $teamData['city'],
                    $teamData['abbreviation'],
                    $teamData['id']
                ));
            }
        }
        return $teamDTOs;
    }
}