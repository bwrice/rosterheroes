<?php


namespace App\Factories\Models;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
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

    /**
     * @param array $extra
     * @return PlayerGameLog
     */
    public function create(array $extra = [])
    {
        $team = $this->getTeam();
        $player = $this->getPlayer($team);
        $game = $this->getGame($team);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = PlayerGameLog::query()->create(array_merge([
            'player_id' => $player->id,
            'team_id' => $team->id,
            'game_id' => $game->id
        ], $extra));
        return $playerGameLog;
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

    protected function getGame(Team $team)
    {
        if ($this->game) {
            return $this->game;
        }
        return GameFactory::new()->forEitherTeam($team)->create();
    }

    public function forPlayer(Player $player)
    {
        $clone = clone $this;
        $clone->player = $player;
        return $clone;
    }

    public function forTeam(Team $team)
    {
        $clone = clone $this;
        $clone->team = $team;
        return $clone;
    }

    public function forGame(Game $game)
    {
        $clone = clone $this;
        $clone->game = $game;
        return $clone;
    }
}
