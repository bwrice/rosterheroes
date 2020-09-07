<?php

namespace App;

use App\Domain\Models\Hero;
use App\Domain\Models\SquadSnapshot;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HeroSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $squad_snapshot_id
 * @property int $hero_id
 *
 * @property Hero $hero
 * @property SquadSnapshot $squadSnapshot
 */
class HeroSnapshot extends Model
{
    protected $guarded = [];

    public function squadSnapshot()
    {
        return $this->belongsTo(SquadSnapshot::class);
    }

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }
}
