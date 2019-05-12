<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Collections\TeamCollection;
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
use Carbon\CarbonImmutable;
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

    public function getPlayerDTOs(League $league): Collection
    {
        $teams = $league->teams;
        $positions = $league->sport->positions;
        $data = $this->playerAPI->getData($league);
        return collect($data)->map(function ($playerData) use ($teams, $positions) {

            $team = $this->getTeamForPlayerDTO($teams, $playerData);
            $playerPositions = $this->getPositionsForPlayerDTO($positions, $playerData);

            if ($team && $playerPositions->isNotEmpty()) {
                return new PlayerDTO(
                    $team,
                    $playerPositions,
                    $playerData['firstName'],
                    $playerData['lastName'],
                    $playerData['id']
                );
            }
            return null;
        })->filter(); // filter nulls
    }

    public function getTeamForPlayerDTO(Collection $teams, array $playerData): ?Team
    {
        if (! empty($playerData['teamAsOfDate']['id'])) {
            $team = $teams->where('external_id', '=', $playerData['teamAsOfDate']['id'])->first();
            if ($team) {
                return $team;
            } else {
                Log::warning("MySportsFeed player has team ID set but team wasn't found", [
                    'playerData' => $playerData,
                    'teams' => $teams->toArray()
                ]);
            }
        }
        return null;
    }

    protected function getPositionsForPlayerDTO(PositionCollection $positions, $playerData)
    {
        $totalPositions = $playerData['alternatePositions'][] = $playerData['primaryPosition'];

        $abbreviations = collect($totalPositions)->map(function ($positionAbbreviation) {
            // We only use outfield (OF) for all outfield positions
            switch($positionAbbreviation) {
                case 'LF':
                case 'RF':
                case 'CF':
                $positionAbbreviation = 'OF';
            }

            return $positionAbbreviation;
        });

        $filteredPositions = $positions->whereIn('abbreviation', $abbreviations);

        if ($filteredPositions->isEmpty()) {
            Log::warning("MySportsFeed player couldn't find valid positions", [
                'playerData' => $playerData,
                'positions' => $positions->toArray(),
                '$filteredPositions' => $filteredPositions->toArray()
            ]);
        }

        return $filteredPositions;
    }

    /**
     * @param League $league
     *
     * @return Collection
     */
    public function getTeamDTOs(League $league): Collection
    {
        $data = $this->teamAPI->getData($league);
        return collect($data)->map(function ($team) use ($league) {
            return new TeamDTO(
                $league,
                $team['name'],
                $team['city'],
                $team['abbreviation'],
                $team['id']
            );
        });
    }

    public function getGameDTOs(League $league): Collection
    {
        $teams = $league->teams;
        $data = $this->gameAPI->getData($league);
        return collect($data)->map(function ($gameData) use ($teams) {
            try {
                $scheduleData = $gameData['schedule'];
                $homeAndAwayTeams = $this->getTeamsFromSchedule($scheduleData, $teams);
                $startsAt = CarbonImmutable::parse($scheduleData['startTime']);
                return new GameDTO(
                    $startsAt,
                    $homeAndAwayTeams['home_team'],
                    $homeAndAwayTeams['away_team'],
                    $scheduleData['id']
                );

            } catch (\Exception $exception) {
                Log::warning("Failed to convert MySportsFeed game data into Game DTO", [
                    'exceptionMessage' => $exception->getMessage(),
                    'gameData' => $gameData,
                    'teams' => $teams->toArray()
                ]);
                return null;
            }
        })->filter(); // filter nulls
    }

    protected function getTeamsFromSchedule(array $scheduleData, TeamCollection $teams)
    {
        $awayTeam = $teams->where('external_id', '=', $scheduleData['awayTeam']['id'])->first();
        if (! $awayTeam) {
            throw new \RuntimeException("Couldn't find Away Team");
        }
        $homeTeam = $teams->where('external_id', '=', $scheduleData['homeTeam']['id'])->first();
        if (! $homeTeam) {
            throw new \RuntimeException("Couldn't find Home Team");
        }
        return [
            'away_team' => $awayTeam,
            'home_team' => $homeTeam
        ];
    }

    public function getPlayerGameLogDTOs(Team $league): Collection
    {
        // TODO: Implement getPlayerGameLogDTOs() method.
    }
}