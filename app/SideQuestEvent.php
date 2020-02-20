<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestEvent
 * @package App
 *
 * @property array $data
 * @property int $moment
 *
 * @property SideQuestResult $sideQuestResult
 */
class SideQuestEvent extends Model
{
    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    public function sideQuestResult()
    {
        return $this->belongsTo(SideQuestResult::class);
    }
}
