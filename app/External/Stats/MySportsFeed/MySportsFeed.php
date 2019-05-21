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
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Team;
use App\Domain\DataTransferObjects\TeamDTO;
use App\External\Stats\MySportsFeed\StatConverters\StatConverterFactory;
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
    /**
     * @var GameLogAPI
     */
    private $gameLogAPI;
    /**
     * @var StatConverterFactory
     */
    private $statConverterFactory;

    public function __construct(
        PlayerAPI $playerAPI,
        TeamAPI $teamAPI,
        GameAPI $gameAPI,
        GameLogAPI $gameLogAPI,
        StatConverterFactory $statConverterFactory)
    {
        $this->playerAPI = $playerAPI;
        $this->teamAPI = $teamAPI;
        $this->gameAPI = $gameAPI;
        $this->gameLogAPI = $gameLogAPI;
        $this->statConverterFactory = $statConverterFactory;
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
        return collect($data)->map(function ($playerData) use ($teams, $positions, $league) {

            $team = $this->getTeamForPlayerDTO($teams, $playerData);
            $playerPositions = $this->getPositionsForPlayerDTO($positions, $league, $playerData);

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

    protected function getPositionsForPlayerDTO(PositionCollection $positions, League $league, $playerData)
    {
        $totalPositions = $playerData['alternatePositions'][] = $playerData['primaryPosition'];

        $positionNames = collect($totalPositions)->map(function ($positionAbbreviation) use ($league) {
            // We only use outfield (OF) for all outfield positions
            return $this->convertAbbreviateToPositionName($positionAbbreviation, $league);
        });

        $filteredPositions = $positions->whereIn('name', $positionNames);

        if ($filteredPositions->isEmpty()) {
            Log::warning("MySportsFeed player couldn't find valid positions", [
                'playerData' => $playerData,
                'positions' => $positions->toArray(),
                '$filteredPositions' => $filteredPositions->toArray()
            ]);
        }

        return $filteredPositions;
    }

    protected function convertAbbreviateToPositionName(string $abbreviation, League $league)
    {
        switch ($abbreviation) {
            case 'QB':
                return Position::QUARTERBACK;
            case 'RB':
                return Position::RUNNING_BACK;
            case 'WR':
                return Position::WIDE_RECEIVER;
            case 'TE':
                return Position::TIGHT_END;
            case 'P':
                return Position::PITCHER;
            case '1B':
                return Position::FIRST_BASE;
            case '2B':
                return Position::SECOND_BASE;
            case '3B':
                return Position::THIRD_BASE;
            case 'SS':
                return Position::SHORTSTOP;
            case 'LF':
            case 'RF':
            case 'CF':
                return Position::OUTFIELD;
            case 'SF':
                return Position::SMALL_FORWARD;
            case 'PF':
                return Position::POWER_FORWARD;
            case 'SG':
                return Position::SHOOTING_GUARD;
            case 'PG':
                return Position::POINT_GUARD;
            case 'LW':
                return Position::LEFT_WING;
            case 'RW':
                return Position::RIGHT_WING;
            case 'D':
                return Position::DEFENSEMAN;
            case 'G':
                return Position::GOALIE;
            case 'C':
                if ($league->abbreviation === League::MLB) {
                    return Position::CATCHER;
                } elseif ($league->abbreviation === League::NHL) {
                    return Position::HOCKEY_CENTER;
                } else {
                    return Position::BASKETBALL_CENTER;
                }
        }
        return '';
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

    public function getPlayerGameLogDTOs(Team $team): Collection
    {
        $data = $this->gameLogAPI->getData($team);
        return collect($data)->map(function ($gameLogData) use ($team) {

            try {
                return $this->buildPlayerGameDTO($team, $gameLogData);
            } catch (MySportsFeedsException $exception) {

            } catch (\Throwable $error) {
                Log::error("Error while getting game log DTOs", [
                    'error' => $error->getMessage(),
                    'game_log_data' => $gameLogData,
                    'team' => $team->toArray()
                ]);
            }

            return null;
        })->filter(); // filter nulls
    }

    protected function buildPlayerGameDTO(Team $team, array $gameLogData)
    {
        $game = Game::query()->externalID($gameLogData['game']['id'])->first();
        if (! $game) {
            Log::warning("Couldn't find game when building game log DTO", [
                'game_log_data' => $gameLogData,
                'team' => $team->toArray()
            ]);
        }
        $player = Player::query()->externalID($gameLogData['player']['id'])->first();
        if (! $player) {
            Log::warning("Couldn't find player when building game log DTO", [
                'game_log_data' => $gameLogData,
                'team' => $team->toArray()
            ]);
        }
        if ($game && $player) {
            $statConvert = $this->statConverterFactory->getStatConverter($team->league);

        }
    }
}