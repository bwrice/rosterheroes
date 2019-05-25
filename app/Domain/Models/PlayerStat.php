<?php

namespace App\Domain\Models;

use App\Domain\Collections\PlayerStatCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerStat
 * @package App\Domain\Models
 *
 * @property StatType $statType
 * @property PlayerGameLog $playerGameLog
 * @property float $amount
 */
class PlayerStat extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new PlayerStatCollection($models);
    }

    public function statType()
    {
        return $this->belongsTo(StatType::class);
    }

    public function playerGameLog()
    {
        return $this->belongsTo(PlayerGameLog::class);
    }

    /**
     * @return float|int
     */
    public function totalPoints()
    {
        return $this->statType->getBehavior()->getTotalPoints($this->amount);
    }
}
