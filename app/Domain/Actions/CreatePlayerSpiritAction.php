<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/14/19
 * Time: 10:14 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\PlayerSpiritAggregate;
use App\Domain\Collections\PlayerGameLogCollection;
use App\Domain\Math\WeightedValue;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Position;
use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class CreatePlayerSpiritAction
{
    /**
     * @var Week
     */
    private $week;
    /**
     * @var Game
     */
    private $game;
    /**
     * @var Player
     */
    private $player;
    /**
     * @var Position
     */
    private $position;

    public function __construct(
        Week $week,
        Game $game,
        Player $player,
        Position $position)
    {
        $this->week = $week;
        $this->game = $game;
        $this->player = $player;
        $this->position = $position;
    }

    /**
     * @return PlayerSpirit
     * @throws \Exception
     */
    public function __invoke(): PlayerSpirit
    {
        $essenceCost = $this->getEssenceCost();

        $playerSpiritUuid = Str::uuid();

        /** @var PlayerSpiritAggregate $aggregate */
        $aggregate = PlayerSpiritAggregate::retrieve($playerSpiritUuid);

        $aggregate->createPlayerSpirit($this->week->id, $this->player->id, $this->game->id, $essenceCost, PlayerSpirit::STARTING_ENERGY)
            ->persist();

        return PlayerSpirit::uuid($playerSpiritUuid);
    }

    /**
     * @return PlayerGameLogCollection
     */
    protected function getPlayerGameLogs()
    {
        if ($this->player->relationLoaded('playerGameLogs')) {
            return $this->player->playerGameLogs->take($this->getGamesToConsider());
        }

        /** @var PlayerGameLogCollection $playerGameLogs */
        $playerGameLogs = $this->player->playerGameLogs()->take($this->getGamesToConsider())->get();
        return $playerGameLogs;
    }

    /**
     * @return int
     * @throws \MathPHP\Exception\BadDataException
     */
    protected function getEssenceCost()
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
    protected function getGamesToConsider()
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