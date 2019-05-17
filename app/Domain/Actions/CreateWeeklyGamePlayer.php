<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/14/19
 * Time: 10:14 PM
 */

namespace App\Domain\Actions;


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
     * @var Collection
     */
    private $playerGameLogs;

    public function __construct(Week $week, Game $game, Player $player, Collection $playerGameLogs)
    {
        $this->week = $week;
        $this->game = $game;
        $this->player = $player;
        $this->playerGameLogs = $playerGameLogs;
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

    protected function getSalary()
    {
        if ($this->playerGameLogs->isEmpty()) {
            return $this->getDefaultSalary();
        }

        //TODO use playerGameLogs
    }

    protected function getDefaultSalary()
    {
        $highestSalaryPosition = $this->player->positions->withHighestDefaultSalary();
        if ( ! $highestSalaryPosition ) {
            throw new InvalidPlayerException($this->player, $this->player->fullName() . " has zero positions");
        }

        return $highestSalaryPosition->getBehavior()->getDefaultSalary();
    }
}