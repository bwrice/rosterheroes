<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\Domain\Models\Team;
use App\Domain\DataTransferObjects\TeamDTO;
use App\External\Stats\StatsIntegration;
use App\Domain\Models\League;
use App\Domain\Models\Position;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Week;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
        /** @var \App\Domain\Collections\PositionCollection $positions */
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

    public function getGameDTOs(Week $week): Collection
    {
        $teams = Team::all();
        $gameDTOs = collect();
        $data = $this->gameAPI->getData();
        foreach($data as $gameArray) {
            $scheduleData = $gameArray['schedule'];
            $awayTeam = $teams->where('external_id', '=', $scheduleData['awayTeam']['id'])->first();
            $homeTeam = $teams->where('external_id', '=', $scheduleData['awayTeam']['id'])->first();
            if (! ($awayTeam && $homeTeam)) {
                Log::warning("Couldn't find team when getting game DTOs", [
                    'game_data' => $gameArray
                ]);
                continue;
            }
            $startsAt = Carbon::parse($scheduleData['startTime']);
            $dto = new GameDTO($startsAt, $homeTeam, $awayTeam, $scheduleData['external_id']);
            $gameDTOs->push($dto);
        }
        return $gameDTOs;
    }
}