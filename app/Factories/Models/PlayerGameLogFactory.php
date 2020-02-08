<?php


namespace App\Factories\Models;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Team;

class PlayerGameLogFactory
{
    /** @var Player */
    protected $player;

    /** @var Team */
    protected $team;

    /** @var Game */
    protected $game;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $team = $this->getTeam();
        $player = $this->getPlayer($team);

        /** @var Game $game */
        $game = Game::query()->create(array_merge([
            'player_id' => $homeTeam->id,
            'team_id' => $awayTeam->id,
            'game_id' => $this->getStartsAt()
        ], $extra));
        return $game;
    }

    /**
     * @return Team
     */
    protected function getTeam()
    {
        if ($this->team) {
            return $this->team;
        }
        return factory(Team::class)->create();
    }

    protected function getPlayer(Team $team)
    {
        if ($this->player) {
            return $this->player;
        }
        return PlayerFactory::new()->forTeam($team)->create();
    }
}
