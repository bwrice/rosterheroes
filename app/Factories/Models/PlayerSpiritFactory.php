<?php


namespace App\Factories\Models;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Illuminate\Support\Str;

class PlayerSpiritFactory
{
    /** @var Week */
    protected $week;

    /** @var Player */
    protected $player;

    /** @var Game */
    protected $game;

    /** @var GameFactory */
    protected $gameFactory;

    /** @var PlayerGameLogFactory */
    protected $playerGameLogFactory;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $player = $this->getPlayer();
        $week = $this->getWeek();
        $game = $this->getGame($player, $week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = PlayerSpirit::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'week_id' => $week->id,
            'player_id' => $player->id,
            'game_id' => $game->id,
            'essence_cost' => 5000,
            'energy' => PlayerSpirit::STARTING_ENERGY
        ], $extra));

        if ($this->playerGameLogFactory) {
            $playerGameLog = $this->playerGameLogFactory
                ->forPlayer($player)
                ->forTeam($player->team)
                ->forGame($game)
                ->create();
            $playerSpirit->player_game_log_id = $playerGameLog->id;
            $playerSpirit->save();
        }

        return $playerSpirit->fresh();
    }

    /**
     * @return Player
     */
    protected function getPlayer()
    {
        if ($this->player) {
            return $this->player;
        }
        return factory(Player::class)->create();
    }

    /**
     * @param Player $player
     * @param Week $week
     * @return Game
     */
    protected function getGame(Player $player, Week $week)
    {
        if ($this->game) {
            return $this->game;
        }
        $gameFactory = $this->gameFactory ?: GameFactory::new();
        return $gameFactory->forEitherTeam($player->team)->forWeek($week)->create();
    }

    /**
     * @return Week
     */
    protected function getWeek()
    {
        if ($this->week) {
            return $this->week;
        }
        return factory(Week::class)->create();
    }

    public function forCurrentWeek()
    {
        $clone = clone $this;
        $this->week = CurrentWeek::get();
        return $clone;
    }

    public function withPlayerGameLog(PlayerGameLogFactory $playerGameLogFactory = null)
    {
        $clone = clone $this;
        $clone->playerGameLogFactory = $playerGameLogFactory ?: PlayerGameLogFactory::new();
        return $clone;
    }
}
