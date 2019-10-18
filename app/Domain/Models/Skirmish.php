<?php

namespace App\Domain\Models;

use App\Domain\Models\Quest;
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
    protected $guarded = [];

    public function quests()
    {
        return $this->belongsToMany(Quest::class);
    }
}
