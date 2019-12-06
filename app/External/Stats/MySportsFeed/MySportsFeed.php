<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Collections\GameCollection;
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
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatAmountDTOBuilderFactory;
use App\External\Stats\MySportsFeed\StatAmountDTOs\StatConverterFactory;
use App\External\Stats\StatsIntegration;
use App\Domain\Models\League;
use App\Domain\Models\Position;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Week;
use App\ExternalPlayer;
use App\StatsIntegrationType;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class MySportsFeed implements StatsIntegration
{
    public const INTEGRATION_NAME = 'my-sports-feed';

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
        GameLogAPI $gameLogAPI,
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
        $teams = $league->teams;
        $positions = $league->sport->positions;
        $data = $this->playerAPI->getData($league);
        return collect($data)->map(function ($playerArray) use ($teams, $positions, $league) {

            $team = $this->getTeamForPlayerDTO($teams, $playerArray);
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

    public function getGameDTOs(League $league, int $yearDelta = 0): Collection
    {
        $teams = $league->teams;
        $data = $this->gameAPI->getData($league, $yearDelta);
        return collect($data)->map(function ($gameData) use ($teams) {
            try {
                $scheduleData = $gameData['schedule'];
                $homeAndAwayTeams = $this->getTeamsFromSchedule($scheduleData, $teams);
                $startsAt = Date::parse($scheduleData['startTime']);
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

    public function getPlayerGameLogDTOs(Game $game, PositionCollection $positions, int $yearDelta): Collection
    {
        $data = $this->gameLogAPI->getData($game, $yearDelta);
        $awayPlayersData = $data['away']['players'];
        $homePlayersData = $data['away']['players'];
        $players = $this->getPlayers(array_merge($awayPlayersData, $homePlayersData));
        $statTypes = StatType::all();
        $awayTeamDTOs = $this->buildGameLogDTOs($game, $game->awayTeam, $players, $statTypes, $awayPlayersData);
        $homeTeamDTOs = $this->buildGameLogDTOs($game, $game->homeTeam, $players, $statTypes, $homePlayersData);
        return $awayTeamDTOs->merge($homeTeamDTOs);
    }

    protected function getPlayers(array $playerResponseData)
    {
        $externalIDs = collect($playerResponseData)->map(function ($playerData) {
            return $playerData['player']['id'];
        });
        return Player::query()->forIntegration($this->getIntegrationType()->id, $externalIDs)->with('externalPlayers')->get();
    }

    protected function buildGameLogDTOs(Game $game, Team $team, PlayerCollection $players, StatTypeCollection $statTypes, array $playersResponseData)
    {
        $integrationTypeID = $this->getIntegrationType()->id;
        return collect($playersResponseData)->map(function ($playerData) use ($game, $team, $players, $statTypes, $integrationTypeID) {

            $matchingPlayer = $players->first(function (Player $player) use ($integrationTypeID, $playerData) {
                $matchingExternalPlayer = $player->externalPlayers->first(function (ExternalPlayer $externalPlayer) use ($integrationTypeID, $playerData) {
                    return $externalPlayer->int_type_id === $integrationTypeID && $externalPlayer->external_id === (string) $playerData['player']['id'];
                });
                return ! is_null($matchingExternalPlayer);
            });

            if ($matchingPlayer) {

                $statAmountDTOBuilder = $this->statAmountDTOBuilderFactory->getStatAmountDTOBuilder($team->league);
                $statAmountDTOs = $statAmountDTOBuilder->getStatAmountDTOs($statTypes, $playerData['playerStats']);

                return new PlayerGameLogDTO($matchingPlayer, $game, $team, $statAmountDTOs);
            } else {
                // TODO: Handle non-matching player
                return null;
            }
        })->filter(); // filter nulls
    }

//    protected function buildPlayerGameLogDTO(Team $team, GameCollection $games, PlayerCollection $players, StatTypeCollection $statTypes, array $gameLogData)
//    {
//        $game = $games->where('external_id', '=', $gameLogData['game']['id'])->first();
//        if (! $game) {
//            throw new MySportsFeedsException("Couldn't find game when building game log DTO");
//        }
//        if (! ($game->homeTeam->id === $team->id || $game->awayTeam->id === $team->id ) ) {
//            throw new MySportsFeedsException("Team doesn't belong to game");
//        }
//        $player = $players->where('external_id' ,$gameLogData['player']['id'])->first();
//        if (! $player) {
//            throw new MySportsFeedsException("Couldn't find player when building game log DTO");
//        }
//        $statAmountDTOBuilder = $this->statAmountDTOBuilderFactory->getStatAmountDTOBuilder($team->league);
//        $statAmountDTOs = $statAmountDTOBuilder->getStatAmountDTOs($statTypes, $gameLogData['stats']);
//        return new PlayerGameLogDTO($player, $game, $team, $statAmountDTOs);
//    }

    public function getIntegrationType(): StatsIntegrationType
    {
        return StatsIntegrationType::forNameOrFail(self::INTEGRATION_NAME);
    }
}
