<?php

namespace App;

use App\Domain\Models\EventSourcedModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestEvent
 * @package App
 *
 * @property array $data
 * @property string $uuid
 * @property int $moment
 *
 * @property SideQuestResult $sideQuestResult
 */
class SideQuestEvent extends EventSourcedModel
{
    protected $guarded = [];
    protected $casts = [
        'event' => 'array'
    ];

    public function sideQuestResult()
    {
        return $this->belongsTo(SideQuestResult::class);
    }
}
