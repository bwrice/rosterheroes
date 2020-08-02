<?php


namespace App\Factories\Models;


use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

class GameFactory
{
    /** @var League */
    protected $league;

    /** @var Team */
    protected $homeTeam;

    /** @var Week */
    protected $week;

    /** @var Team */
    protected $awayTeam;

    /** @var CarbonInterface|null */
    protected $startsAt;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $homeTeam = $this->getHomeTeam();
        $awayTeam = $this->getAwayTeam($homeTeam->league);

        /** @var Game $game */
        $game = Game::query()->create(array_merge([
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'starts_at' => $this->getStartsAt(),
            'season_type' => Game::SEASON_TYPE_REGULAR
        ], $extra));
        return $game;
    }

    /**
     * @return Team
     */
    protected function getHomeTeam()
    {
        if ($this->homeTeam) {
            return $this->homeTeam;
        }
        if ($this->league) {
            return factory(Team::class)->create(['league_id' => $this->league->id]);
        }
        return factory(Team::class)->create();
    }

    /**
     * @param League $homeTeamLeague
     * @return Team
     */
    protected function getAwayTeam(League $homeTeamLeague)
    {
        if ($this->awayTeam) {
            return $this->awayTeam;
        }
        return factory(Team::class)->create(['league_id' => $homeTeamLeague->id]);
    }

    public function forHomeTeam(Team $team)
    {
        $clone = clone $this;
        $clone->homeTeam = $team;
        return $clone;
    }

    public function forAwayTeam(Team $team)
    {
        $clone = clone $this;
        $clone->awayTeam = $team;
        return $clone;
    }

    public function forEitherTeam(Team $team)
    {
        if (rand(1,2) == 1) {
            return $this->forHomeTeam($team);
        }
        return $this->forAwayTeam($team);
    }

    public function forWeek(Week $week)
    {
        $clone = clone $this;
        $clone->week = $week;
        return $clone;
    }

    public function withStartTime(CarbonInterface $startsAt)
    {
        $clone = clone $this;
        $clone->startsAt = $startsAt;
        return $clone;
    }

    protected function getStartsAt()
    {
        if ($this->startsAt) {
            return $this->startsAt;
        }
        if ($this->week) {
            return $this->week->adventuring_locks_at->addHours(3);
        }
        return Date::now()->addHours(3);
    }
}
