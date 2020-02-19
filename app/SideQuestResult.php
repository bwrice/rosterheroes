<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestResult
 * @package App
 *
 * @property Collection $sideQuestEvents
 */
class SideQuestResult extends Model
{
    protected $guarded = [];

    public function sideQuestEvents()
    {
        return $this->hasMany(SideQuestEvent::class);
    }
}
