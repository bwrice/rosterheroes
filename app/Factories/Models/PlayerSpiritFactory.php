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

    /** @var PlayerGameLogFactory */
    protected $playerGameLogFactory;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $week = $this->getWeek();

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = PlayerSpirit::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'week_id' => $week->id,
            'player_game_log_id' => $this->getPlayerGameLogID(),
            'essence_cost' => 5000,
            'energy' => PlayerSpirit::STARTING_ENERGY
        ], $extra));

        return $playerSpirit->fresh();
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

    protected function getPlayerGameLogID()
    {
        if ($this->playerGameLogFactory) {
            $playerGameLogFactory = $this->playerGameLogFactory;
        } else {
            $playerGameLogFactory = PlayerGameLogFactory::new();
        }
        return $playerGameLogFactory->create()->id;
    }
}
