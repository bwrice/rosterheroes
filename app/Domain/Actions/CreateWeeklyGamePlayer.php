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
use App\Domain\Models\Week;
use App\Domain\Models\WeeklyGamePlayer;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class CreateWeeklyGamePlayer
{
    public const SALARY_PER_POINT = 500;

    // TODO: make this dynamic based on sport?
    public const GAMES_TO_CONSIDER = 15;

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

    public function __construct(Week $week, Game $game, Player $player)
    {
        $this->week = $week;
        $this->game = $game;
        $this->player = $player;
    }

    /**
     * @return WeeklyGamePlayer
     * @throws \Exception
     */
    public function __invoke(): WeeklyGamePlayer
    {
        if ( ! $this->game->starts_at->isBetween($this->week->everything_locks_at, $this->week->ends_at)) {
            throw new InvalidGameException($this->game);
        }

        if ( ! $this->game->hasTeam($this->player->team) ) {
            throw new InvalidPlayerException($this->player);
        }

        $salary = $this->getSalary();

        return WeeklyGamePlayer::createWithAttributes([
            'player_id' => $this->player->id,
            'game_id' => $this->game->id,
            'week_id' => $this->week->id,
            'salary' => $salary
        ]);
    }

    /**
     * @return PlayerGameLogCollection
     */
    protected function getPlayerGameLogs()
    {
        if ($this->player->relationLoaded('playerGameLogs')) {
            return $this->player->playerGameLogs->take(self::GAMES_TO_CONSIDER);
        }

        /** @var PlayerGameLogCollection $playerGameLogs */
        $playerGameLogs = $this->player->playerGameLogs()->take(self::GAMES_TO_CONSIDER)->get();
        return $playerGameLogs;
    }

    /**
     * @return int
     * @throws \MathPHP\Exception\BadDataException
     */
    protected function getSalary()
    {
        $weightedValues = $this->getPlayerGameLogs()->toWeightedValues();
        $weightedValues->push($this->getDefaultWeightedValue());

        $weightedPoints = $weightedValues->getWeightedMean();
        $weightedSalary = $this->convertPointsToSalary($weightedPoints);

        return (int) max($weightedSalary, $this->getMinimumSalary());
    }

    protected function convertPointsToSalary($points)
    {
        return (int) round($points * self::SALARY_PER_POINT);
    }

    protected function getDefaultSalary()
    {
        $highestValuedPosition = $this->getHighestValuedPosition();

        return $highestValuedPosition->getBehavior()->getDefaultSalary();
    }

    protected function getMinimumSalary()
    {
        $highestValuedPosition = $this->getHighestValuedPosition();

        return $highestValuedPosition->getBehavior()->getMinimumSalary();
    }

    protected function getDefaultWeightedValue()
    {
        return new WeightedValue(10, $this->getDefaultSalary() / self::SALARY_PER_POINT);
    }

    /**
     * @return \App\Domain\Models\Position|null
     */
    protected function getHighestValuedPosition()
    {
        $highestValuePosition = $this->player->positions->withHighestPositionValue();
        if (!$highestValuePosition) {
            throw new InvalidPlayerException($this->player, $this->player->fullName() . " has zero positions");
        }
        return $highestValuePosition;
    }
}