<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerStat
 * @package App\Domain\Models
 *
 * @property StatType $statType
 * @property PlayerGameLog $playerGameLog
 */
class PlayerStat extends Model
{
    protected $guarded = [];

    public function statType()
    {
        return $this->belongsTo(StatType::class);
    }

    public function playerGameLog()
    {
        return $this->belongsTo(PlayerGameLog::class);
    }
}
