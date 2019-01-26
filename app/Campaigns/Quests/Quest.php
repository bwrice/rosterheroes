<?php

namespace App\Campaigns\Quests;

use App\EventSourcedModel;
use App\Province;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Quest
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $uuid
 * @property Carbon $completed_at
 *
 * @property Province $province
 */
class Quest extends EventSourcedModel
{
    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'completed_at'
    ];

    public function newCollection(array $models = [])
    {
        return new QuestCollection($models);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function isCompleted()
    {
        return $this->completed_at != null;
    }
}
