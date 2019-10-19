<?php

namespace App\Domain\Models;

use App\Domain\Models\Quest;
use App\Domain\Traits\HasNameSlug;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Skirmish
 * @package App
 *
 * @property int $quest_id
 * @property string $uuid
 */
class Skirmish extends Model
{
    use HasNameSlug;

    protected $guarded = [];

    public function quests()
    {
        return $this->belongsToMany(Quest::class);
    }

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withPivot('count')->withTimestamps();
    }
}
