<?php

namespace App\Domain\Models;

use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\Week;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SquadSnapshot
 * @package App
 *
 * @property int $id
 * @property int $week_id
 * @property int $squad_id
 * @property int $experience
 * @property int $squad_rank_id
 *
 * @property Week $week
 * @property Squad $squad
 * @property SquadRank $squadRank
 */
class SquadSnapshot extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function squadRank()
    {
        return $this->belongsTo(SquadRank::class);
    }
}
