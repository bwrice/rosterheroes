<?php

namespace App\Domain\Models;

use App\Domain\Collections\MinionCollection;
use App\Domain\Collections\QuestCollection;
use App\Domain\Collections\SkirmishCollection;
use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\Province;
use App\Domain\Traits\HasNameSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Quest
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property string $uuid
 * @property int $level
 * @property int $province_id
 * @property int $travel_type_id
 * @property Carbon $completed_at
 *
 * @property Province $province
 *
 * @property SkirmishCollection $skirmishes
 * @property MinionCollection $minions
 */
class Quest extends EventSourcedModel
{
    use HasNameSlug;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'completed_at'
    ];

    public static function resourceRelations()
    {
        return [
            'minions',
            'titans',
            'skirmishes.minions.attacks'
        ];
    }

    public function newCollection(array $models = [])
    {
        return new QuestCollection($models);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function travelType()
    {
        return $this->belongsTo(TravelType::class);
    }

    public function minions()
    {
        return $this->belongsToMany(Minion::class)->withPivot('weight')->withTimestamps();
    }

    public function titans()
    {
        return $this->belongsToMany(Titan::class)->withPivot('count')->withTimestamps();
    }

    public function skirmishes()
    {
        return $this->belongsToMany(Skirmish::class)->withTimestamps();
    }

    public function isCompleted()
    {
        return $this->completed_at != null;
    }
}
