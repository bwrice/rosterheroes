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
    protected $weekID;

    protected $playerID;

    protected $gameID;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = PlayerSpirit::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'week_id' => $this->weekID ?: CurrentWeek::id(),
            'player_id' => $this->playerID ?: $this->getPlayer()->id,
            'game_id' => $this->gameID ?: $this->getGame()->id,
            'essence_cost' => 5000,
            'energy' => PlayerSpirit::STARTING_ENERGY
        ], $extra));

        return $playerSpirit;
    }

    /**
     * @return Player
     */
    protected function getPlayer()
    {
        return factory(Player::class)->create();
    }

    /**
     * @return Game
     */
    protected function getGame()
    {
        return factory(Game::class)->create();
    }

    /**
     * @return Week
     */
    protected function getWeek()
    {
        return factory(Week::class)->create();
    }

    public function forCurrentWeek()
    {
        $clone = clone $this;
        $this->weekID = CurrentWeek::id();
        return $clone;
    }
}
