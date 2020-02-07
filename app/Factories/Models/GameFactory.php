<?php


namespace App\Factories\Models;


use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Team;

class GameFactory
{
    /** @var League */
    protected $league;

    /** @var Team */
    protected $homeTeam;

    /** @var Team */
    protected $awayTeam;

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
            'away_team_id' => $awayTeam->id
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
        return factory(Team::class)->create(['league_id' => $this->league->id]);
    }
}
