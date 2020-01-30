<?php

namespace App;

use App\Domain\Models\Minion;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MinionSnapshot
 * @package App
 *
 * @property int $id
 * @property int $minion_id
 * @property int $week_id
 *
 * @property Minion $minion
 * @property Week $week
 */
class MinionSnapshot extends Model
{
    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    public function minion()
    {
        return $this->belongsTo(Minion::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }
}
