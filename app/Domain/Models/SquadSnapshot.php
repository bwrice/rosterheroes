<?php

namespace App\Domain\Models;

use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\Week;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SquadSnapshot
 * @package App
 *
 * @property string $uuid
 * @property int $id
 * @property int|null $week_id
 * @property int $squad_id
 * @property int $experience
 * @property int $squad_rank_id
 *
 * @property Week|null $week
 * @property Squad $squad
 * @property SquadRank $squadRank
 *
 * @property Collection $heroSnapshots
 */
class SquadSnapshot extends Model
{
    use HasUuid;

    protected $guarded = [];

    public static function resourceRelations()
    {
        return [
            'heroSnapshots.itemSnapshots.attackSnapshots',
            'heroSnapshots.measurableSnapshots',
            'week'
        ];
    }

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

    public function heroSnapshots()
    {
        return $this->hasMany(HeroSnapshot::class);
    }
}
