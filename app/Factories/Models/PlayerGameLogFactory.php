<?php


namespace App\Factories\Models;


use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Position;
use App\Domain\Models\Sport;
use App\Domain\Models\StatType;
use App\Domain\Models\Team;
use Illuminate\Support\Collection;

class PlayerGameLogFactory
{
    /** @var Player */
    protected $player;

    /** @var Team */
    protected $team;

    /** @var Game */
    protected $game;

    /** @var bool */
    protected $withPlayerStats = false;

    /** @var Collection|null */
    protected $playerStatFactories;

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

        if ($this->withPlayerStats) {
            $this->getPlayerStatFactories($playerGameLog)->each(function (PlayerStatFactory $factory) use ($playerGameLog) {
                $factory->forGameLog($playerGameLog)->create();
            });
        }

        return $playerGameLog->fresh();
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

    public function withStats(Collection $playerStatFactories = null)
    {
        $clone = clone $this;
        $clone->withPlayerStats = true;
        $clone->playerStatFactories = $playerStatFactories;
        return $clone;
    }

    /**
     * @param PlayerGameLog $playerGameLog
     * @return Collection
     */
    protected function getPlayerStatFactories(PlayerGameLog $playerGameLog)
    {
        if ($this->playerStatFactories) {
            return $this->playerStatFactories;
        }
        /** @var Position $position */
        $position = $playerGameLog->player->positions()->inRandomOrder()->first();
        $amount = rand(3, 8);
        if ($position) {
            $statTypes = $this->getStatTypesForFactoryFromPosition($position, $amount);
        } else {
            $sport = $playerGameLog->player->team->league->sport;
            $statTypes = $this->getStatTypesForFactoryFromSport($sport, $amount);
        }
        return $statTypes->map(function (StatType $statType) {
            return PlayerStatFactory::new()->forStatType($statType);
        });
    }

    protected function getStatTypesForFactoryFromPosition(Position $position, int $amount)
    {
        $statTypeNames = $position->getBehavior()->getFactoryStatTypeNames();
        return StatType::query()
            ->whereIn('name', $statTypeNames)
            ->inRandomOrder()
            ->take($amount)->get();
    }

    protected function getStatTypesForFactoryFromSport(Sport $sport, int $amount)
    {
        return $sport->statTypes()->inRandomOrder()->take($amount)->get();
    }
}
