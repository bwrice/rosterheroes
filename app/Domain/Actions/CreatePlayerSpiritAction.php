<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/14/19
 * Time: 10:14 PM
 */

namespace App\Domain\Actions;

use App\Domain\Collections\PlayerGameLogCollection;
use App\Domain\Math\WeightedValue;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Position;
use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\CreatePlayerSpiritException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CreatePlayerSpiritAction
{

    /**
     * @var Week
     */
    protected $week;
    /**
     * @var Game
     */
    protected $game;
    /**
     * @var Player
     */
    protected $player;
    /**
     * @var Position
     */
    protected $position;

    /**
     * @param Week $week
     * @param Game $game
     * @param Player $player
     * @return PlayerSpirit
     * @throws \MathPHP\Exception\BadDataException|CreatePlayerSpiritException
     */
    public function execute(Week $week, Game $game, Player $player): PlayerSpirit
    {
        $this->setProperties($week, $game, $player);
        $this->validateGame();
        $this->validateTeam();
        $this->position = $this->validatePosition();

        /** @var PlayerGameLog $gameLog */
        $gameLog = PlayerGameLog::query()->firstOrCreate([
            'player_id' => $this->player->id,
            'game_id' => $this->game->id
        ], [
            'team_id' => $this->player->team_id
        ]);

        $existingSpirit = $gameLog->playerSpirit;

        if ($existingSpirit) {
            $message = "Player Spirit with ID: " . $existingSpirit->id . " exists for game log: " . $gameLog->id;
            throw new CreatePlayerSpiritException($message, CreatePlayerSpiritException::CODE_SPIRIT_FOR_GAME_LOG_ALREADY_EXISTS);
        }

        $essenceCost = $this->calculateEssenceCost();

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = PlayerSpirit::query()->create([
            'uuid' => (string) Str::uuid(),
            'week_id' => $this->week->id,
            'player_game_log_id' => $gameLog->id,
            'essence_cost' => $essenceCost,
            'energy' => PlayerSpirit::STARTING_ENERGY
        ]);

        return $playerSpirit;
    }

    protected function setProperties(Week $week, Game $game, Player $player)
    {
        $this->week = $week;
        $this->game = $game;
        $this->player = $player;
    }

    protected function validateGame()
    {
        $adventuringLocksAt = $this->week->adventuring_locks_at;
        if ( ! $this->game->starts_at->isBetween($adventuringLocksAt, $adventuringLocksAt->addHours(12))) {
            throw new CreatePlayerSpiritException("Game has invalid starting time", CreatePlayerSpiritException::CODE_INVALID_GAME_TIME);
        }
    }

    protected function validateTeam()
    {
        if (! $this->game->hasTeam($this->player->team)) {
            throw new CreatePlayerSpiritException("Team doesn't belong to game", CreatePlayerSpiritException::CODE_TEAM_NOT_PART_OF_GAME);
        }
    }

    protected function validatePosition()
    {
        $position = $this->player->positions->withHighestPositionValue();
        if (!$position) {
            throw new CreatePlayerSpiritException("No position found for player", CreatePlayerSpiritException::CODE_INVALID_PLAYER_POSITIONS);
        }
        return $position;
    }


    /**
     * @return PlayerGameLogCollection
     */
    protected function getPlayerGameLogs()
    {
        $amount = $this->getAmountOfGamesToConsider();

        /** @var PlayerGameLogCollection $gameLogs */
        $gameLogs = PlayerGameLog::query()->where('player_id', '=', $this->player->id)
            ->whereHas('game', function (\Illuminate\Database\Eloquent\Builder $builder) {
                return $builder->where('starts_at', '<', now()->subHours(5));
            })
            ->join('games', 'games.id', '=', 'player_game_logs.game_id')
            ->orderByDesc('games.starts_at')
            ->select('player_game_logs.*')
            ->with('game')
            ->take($amount)
            ->get();

        return $gameLogs;
    }

    /**
     * @return int
     * @throws \MathPHP\Exception\BadDataException
     */
    protected function calculateEssenceCost()
    {
        $weightedValues = $this->getPlayerGameLogs()->toWeightedValues();

        // Add at least one weighted value to the collection based off default values for the position
        $weightedValues->push($this->getDefaultWeightedValue());

        $weightedPoints = $weightedValues->getWeightedMean();
        $weightedEssenceCost = $this->convertPointsToEssenceCost($weightedPoints);

        return (int) max($weightedEssenceCost, $this->position->getMinimumEssenceCost());
    }

    protected function convertPointsToEssenceCost($points)
    {
        return (int) round($points * PlayerSpirit::ESSENCE_COST_PER_POINT);
    }

    protected function getDefaultWeightedValue()
    {
        return new WeightedValue($this->getDefaultWeight(), $this->getDefaultTotalPoints());
    }

    protected function getDefaultTotalPoints()
    {
        return $this->position->getDefaultEssenceCost() / PlayerSpirit::ESSENCE_COST_PER_POINT;
    }

    /**
     * @return int
     */
    protected function getAmountOfGamesToConsider()
    {
        $gamesPerSeason = $this->position->getGamesPerSeason();
        return $gamesPerSeason > 0 ? (int) ceil($gamesPerSeason/2) : 1;
    }

    /**
     * @return float|int
     */
    protected function getDefaultWeight()
    {
        $gamesPerSeason = $this->position->getGamesPerSeason();
        return $gamesPerSeason > 0 ? $gamesPerSeason / 4 : 1;
    }
}
