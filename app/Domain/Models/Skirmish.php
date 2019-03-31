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
 *
 * @property Quest $quest
 */
class Skirmish extends Model
{
    protected $guarded = [];

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}
