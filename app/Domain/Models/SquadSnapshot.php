<?php

namespace App\Domain\Models;

use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SquadSnapshot
 * @package App
 *
 * @property int $id
 * @property int $squad_id
 * @property int $week_id
 *
 * @property Squad $squad
 * @property Week $week
 */
class SquadSnapshot extends Model
{
    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }
}
