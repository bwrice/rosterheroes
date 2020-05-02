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
    /** @var PlayerFactory|null */
    protected $playerFactory;

    /** @var Player */
    protected $player;

    /** @var Team */
    protected $team;

    /** @var GameFactory|null */
    protected $gameFactory;

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
        $player = $this->getPlayer();
        $game = $this->getGame($player->team);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = PlayerGameLog::query()->create(array_merge([
            'player_id' => $player->id,
            'team_id' => $this->getTeamID($player),
            'game_id' => $game->id
        ], $extra));

        if ($this->withPlayerStats) {
            $this->getPlayerStatFactories($playerGameLog)->each(function (PlayerStatFactory $factory) use ($playerGameLog) {
                $factory->forGameLog($playerGameLog)->create();
            });
        }

        return $playerGameLog->fresh();
    }

    protected function getPlayer()
    {
        if ($this->player) {
            return $this->player;
        }
        $playerFactory = $this->playerFactory ?: PlayerFactory::new();
        if ($this->team) {
            $playerFactory->withTeamID($this->team->id);
        }
        return $playerFactory->create();
    }

    protected function getTeamID(Player $player)
    {
        if ($this->team) {
            return $this->team->id;
        }
        return $player->team->id;
    }

    protected function getGame(Team $team)
    {
        if ($this->game) {
            return $this->game;
        }
        $gameFactory = $this->gameFactory ?: GameFactory::new()->forEitherTeam($team);
        return $gameFactory->create();
    }

    public function withPlayer(PlayerFactory $playerFactory)
    {
        $clone = clone $this;
        $clone->playerFactory = $playerFactory;
        return $clone;
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

    public function withGame(GameFactory $gameFactory)
    {
        $clone = clone $this;
        $clone->gameFactory = $gameFactory;
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
            return PlayerStatFactory::new()->forStatType($statType->name);
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

    public function goodRunningBackGame()
    {
        $playerStatFactory = PlayerStatFactory::new();
        return $this->withStats(collect([
            $playerStatFactory->forStatType(StatType::RUSH_YARD)->withAmount(80),
            $playerStatFactory->forStatType(StatType::RUSH_TD)->withAmount(1)
        ]));
    }
}
