<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestEvent
 * @package App
 *
 * @property SideQuestResult $sideQuestResult
 */
class SideQuestEvent extends Model
{
    public function sideQuestResult()
    {
        return $this->belongsTo(SideQuestResult::class);
    }
}
