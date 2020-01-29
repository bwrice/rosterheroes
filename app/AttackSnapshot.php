<?php

namespace App;

use App\Domain\Models\Attack;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AttackSnapshot
 * @package App
 *
 * @property int $id
 * @property int $attack_id
 * @property int $week_id
 */
class AttackSnapshot extends Model
{
    protected $guarded = [];

    public function attack()
    {
        return $this->belongsTo(Attack::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }
}
