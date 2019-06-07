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
use App\Domain\Models\Position;
use App\Domain\Models\Week;
use App\Domain\Models\WeeklyGamePlayer;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class CreateWeeklyGamePlayer
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
     * @return WeeklyGamePlayer
     * @throws \Exception
     */
    public function __invoke(): WeeklyGamePlayer
    {
        $salary = $this->getSalary();

        return WeeklyGamePlayer::createWithAttributes([
            'player_id' => $this->player->id,
            'game_id' => $this->game->id,
            'week_id' => $this->week->id,
            'salary' => $salary,
            'effectiveness' => WeeklyGamePlayer::STARTING_EFFECTIVENESS
        ]);
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
    protected function getSalary()
    {
        $weightedValues = $this->getPlayerGameLogs()->toWeightedValues();

        // Add at least one weighted value to the collection based off default values for the position
        $weightedValues->push($this->getDefaultWeightedValue());

        $weightedPoints = $weightedValues->getWeightedMean();
        $weightedSalary = $this->convertPointsToSalary($weightedPoints);

        return (int) max($weightedSalary, $this->position->getMinimumSalary());
    }

    protected function convertPointsToSalary($points)
    {
        return (int) round($points * WeeklyGamePlayer::SALARY_PER_POINT);
    }

    protected function getDefaultWeightedValue()
    {
        return new WeightedValue($this->getDefaultWeight(), $this->getDefaultTotalPoints());
    }

    protected function getDefaultTotalPoints()
    {
        return $this->position->getDefaultSalary() / WeeklyGamePlayer::SALARY_PER_POINT;
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