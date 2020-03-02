<?php


namespace App\Factories\Models;


use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\StatType;

class PlayerStatFactory
{
    /** @var PlayerGameLog */
    protected $playerGameLog;

    /** @var StatType */
    protected $statType;

    /** @var float */
    protected $amount;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $playerGameLog = $this->getPlayerGameLog();
        $statType = $this->getStatType($playerGameLog);
        $amount = $this->getAmount($statType);

        /** @var PlayerStat $playerStat */
        $playerStat = PlayerStat::query()->create(array_merge([
            'player_game_log_id' => $playerGameLog->id,
            'stat_type_id' => $statType->id,
            'amount' => $amount
        ], $extra));
        return $playerStat;
    }

    public function forGameLog(PlayerGameLog $gameLog)
    {
        $clone = clone $this;
        $clone->playerGameLog = $gameLog;
        return $clone;
    }

    public function forStatType(StatType $statType)
    {
        $clone = clone $this;
        $clone->statType = $statType;
        return $clone;
    }

    public function withAmount(float $amount)
    {
        $clone = clone $this;
        $clone->amount = $amount;
        return $clone;
    }

    protected function getPlayerGameLog()
    {
        if ($this->playerGameLog) {
            return $this->playerGameLog;
        }
        return PlayerGameLogFactory::new()->create();
    }

    protected function getStatType(PlayerGameLog $playerGameLog)
    {
        if ($this->statType) {
            return $this->statType;
        }

        $sport = $playerGameLog->player->team->league->sport;
        return $sport->statTypes()->inRandomOrder()->first();
    }

    protected function getAmount(StatType $statType)
    {
        if (! is_null($this->amount)) {
            return $this->amount;
        }
        $pointsPer = $statType->getBehavior()->getPointsPer();
        if ($pointsPer < 0) {
            return 1;
        }
        $topRange = (int) ceil(100/$pointsPer);
        $rand = rand(1, $topRange);
        return (int) ceil($rand/5);
    }
}
