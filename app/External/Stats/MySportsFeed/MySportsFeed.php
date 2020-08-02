<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Collections\GameLogDTOCollection;
use App\Domain\Collections\PlayerCollection;
use App\Domain\Collections\StatTypeCollection;
use App\Domain\Collections\TeamCollection;
use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\StatType;
use App\Domain\Models\Team;
use App\Domain\DataTransferObjects\TeamDTO;
use App\External\Stats\MySportsFeed\APIs\GameAPI;
use App\External\Stats\MySportsFeed\APIs\PlayerAPI;
use App\External\Stats\MySportsFeed\APIs\TeamAPI;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatAmountDTOBuilderFactory;
use App\External\Stats\StatsIntegration;
use App\Domain\Models\League;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\ExternalPlayer;
use App\Domain\Models\ExternalTeam;
use App\Domain\Models\StatsIntegrationType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class MySportsFeed implements StatsIntegration
{
    public const INTEGRATION_NAME = 'my-sports-feed';
    public const ENV_KEY = 'msf';

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
     * @var BoxScoreAPI
     */
    private $gameLogAPI;
    /**
     * @var StatAmountDTOBuilderFactory
     */
    private $statAmountDTOBuilderFactory;
    /**
     * @var PositionConverter
     */
    private $positionConverter;

    public function __construct(
        PlayerAPI $playerAPI,
        TeamAPI $teamAPI,
        GameAPI $gameAPI,
        BoxScoreAPI $gameLogAPI,
        StatAmountDTOBuilderFactory $statAmountDTOBuilderFactory,
        PositionConverter $positionConverter)
    {
        $this->playerAPI = $playerAPI;
        $this->teamAPI = $teamAPI;
        $this->gameAPI = $gameAPI;
        $this->gameLogAPI = $gameLogAPI;
        $this->statAmountDTOBuilderFactory = $statAmountDTOBuilderFactory;
        $this->positionConverter = $positionConverter;
    }

    public function getPlayerDTOs(League $league): Collection
    {
        /** @var TeamCollection $teams */
        $teams = $league->teams()->with('externalTeams')->get();
        $positions = $league->sport->positions;
        $data = $this->playerAPI->getData($league);
        $integrationType = $this->getIntegrationType();
        return collect($data)->map(function ($playerArray) use ($teams, $positions, $league, $integrationType) {

            $team = $this->getTeamForPlayerDTO($teams, $playerArray, $integrationType);
            $playerData = $playerArray['player'];
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

    public function getTeamForPlayerDTO(Collection $teams, array $playerData, StatsIntegrationType $integrationType): ?Team
    {
        if (! empty($playerData['teamAsOfDate']['id'])) {

            $externalTeamID = $playerData['teamAsOfDate']['id'];
            $team = $teams->first(function (Team $team) use ($externalTeamID, $integrationType) {
                $match = $team->externalTeams->first(function (ExternalTeam $externalTeam) use ($externalTeamID, $integrationType) {
                    return $externalTeam->external_id === (string) $externalTeamID
                        && $externalTeam->integration_type_id === $integrationType->id;
                });
                return ! is_null($match);
            });

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
            return $this->positionConverter->convertAbbreviationToPositionName($positionAbbreviation, $league->abbreviation);
        });

        return $positions->whereIn('name', $positionNames);
    }

    /**
     * @param League $league
     *
     * @param int $yearDelta
     * @return Collection
     */
    public function getTeamDTOs(League $league, int $yearDelta = 0): Collection
    {
        $data = $this->teamAPI->getData($league, $yearDelta);
        return collect($data)->map(function ($team) use ($league) {
            return new TeamDTO(
                $league,
                $team['team']['name'],
                $team['team']['city'],
                $team['team']['abbreviation'],
                $team['team']['id']
            );
        });
    }

    public function getGameDTOs(League $league, int $yearDelta = 0, bool $regularSeason = true): Collection
    {
        /** @var TeamCollection $teams */
        $teams = $league->teams()->with('externalTeams')->get();
        $data = $this->gameAPI->getData($league, $yearDelta, $regularSeason);
        $integrationType = $this->getIntegrationType();
        return collect($data)->map(function ($gameData) use ($teams, $integrationType, $regularSeason) {
            try {
                $scheduleData = $gameData['schedule'];
                $homeAndAwayTeams = $this->getTeamsFromSchedule($scheduleData, $teams, $integrationType);
                $startsAt = Date::parse($scheduleData['startTime']);
                return new GameDTO(
                    $startsAt,
                    $homeAndAwayTeams['home_team'],
                    $homeAndAwayTeams['away_team'],
                    $scheduleData['id'],
                    $scheduleData['scheduleStatus'],
                    $regularSeason ? Game::SEASON_TYPE_REGULAR : Game::SEASON_TYPE_POSTSEASON
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

    protected function getTeamsFromSchedule(array $scheduleData, TeamCollection $teams, StatsIntegrationType $integrationType)
    {
        $awayTeamExternalID = $scheduleData['awayTeam']['id'];
        $awayTeam = $teams->first(function(Team $team) use ($awayTeamExternalID, $integrationType) {
            $match = $team->externalTeams->first(function (ExternalTeam $externalTeam) use ($awayTeamExternalID, $integrationType) {
                return $externalTeam->external_id === (string) $awayTeamExternalID
                    && $externalTeam->integration_type_id === $integrationType->id;
            });
            return ! is_null($match);
        });
        if (! $awayTeam) {
            throw new \RuntimeException("Couldn't find Away Team");
        }
        $homeTeamExternalID = $scheduleData['homeTeam']['id'];
        $homeTeam = $teams->first(function(Team $team) use ($homeTeamExternalID, $integrationType) {
            $match = $team->externalTeams->first(function (ExternalTeam $externalTeam) use ($homeTeamExternalID, $integrationType) {
                return $externalTeam->external_id === (string) $homeTeamExternalID
                    && $externalTeam->integration_type_id === $integrationType->id;
            });
            return ! is_null($match);
        });
        if (! $homeTeam) {
            throw new \RuntimeException("Couldn't find Home Team");
        }
        return [
            'away_team' => $awayTeam,
            'home_team' => $homeTeam
        ];
    }

    public function getGameLogDTOs(Game $game, int $yearDelta): GameLogDTOCollection
    {
        $data = $this->gameLogAPI->getData($game, $this->getIntegrationType()->id, $yearDelta);
        $awayPlayerStatsData = $data['stats']['away']['players'];
        $homePlayerStatsData = $data['stats']['home']['players'];
        $players = $this->getPlayers(array_merge($awayPlayerStatsData, $homePlayerStatsData));
        $statTypes = StatType::all();
        $gameLogs = new GameLogDTOCollection();

        $awayTeamDTOs = $this->buildGameLogDTOs($game, $game->awayTeam, $players, $statTypes, $awayPlayerStatsData);
        $gameLogs = $gameLogs->merge($awayTeamDTOs);
        $homeTeamDTOs = $this->buildGameLogDTOs($game, $game->homeTeam, $players, $statTypes, $homePlayerStatsData);
        $gameLogs = $gameLogs->merge($homeTeamDTOs);

        $playedStatus = $data['game']['playedStatus'] ?? null;
        if ($playedStatus === 'COMPLETED') {
            $gameLogs->setGameOver(true);
        }
        return $gameLogs;
    }

    protected function getPlayers(array $playerResponseData)
    {
        $externalIDs = collect($playerResponseData)->map(function ($playerData) {
            return $playerData['player']['id'];
        })->values()->toArray();
        return Player::query()->forIntegration($this->getIntegrationType()->id, $externalIDs)->with('externalPlayers')->get();
    }

    protected function buildGameLogDTOs(Game $game, Team $team, PlayerCollection $players, StatTypeCollection $statTypes, array $playersResponseData)
    {
        $integrationTypeID = $this->getIntegrationType()->id;
        return collect($playersResponseData)->map(function ($playerData) use ($game, $team, $players, $statTypes, $integrationTypeID) {

            $matchingPlayer = $players->first(function (Player $player) use ($integrationTypeID, $playerData) {
                $matchingExternalPlayer = $player->externalPlayers->first(function (ExternalPlayer $externalPlayer) use ($integrationTypeID, $playerData) {
                    return $externalPlayer->integration_type_id === $integrationTypeID && $externalPlayer->external_id === (string) $playerData['player']['id'];
                });
                return ! is_null($matchingExternalPlayer);
            });

            if ($matchingPlayer) {

                $statAmountDTOBuilder = $this->statAmountDTOBuilderFactory->getStatAmountDTOBuilder($team->league);
                // Poorly formatted JSON from MySportsFeeds means we need first value of array
                $statAmountDTOs = $statAmountDTOBuilder->getStatAmountDTOs($statTypes, $playerData['playerStats'][0]);

                return new PlayerGameLogDTO($matchingPlayer, $game, $team, $statAmountDTOs);
            } else {
                // TODO: Handle non-matching player
                return null;
            }
        })->filter(); // filter nulls
    }

    public function getIntegrationType(): StatsIntegrationType
    {
        return StatsIntegrationType::forNameOrFail(self::INTEGRATION_NAME);
    }
}
