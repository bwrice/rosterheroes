<?php

namespace App;

use App\Domain\Models\Campaign;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestResult
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $campaign_id
 * @property int $side_quest_id
 * @property CarbonInterface|null $rewards_processed_at
 *
 * @property SideQuest $sideQuest
 * @property Campaign $campaign
 *
 * @property Collection $sideQuestEvents
 */
class SideQuestResult extends Model
{
    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'rewards_processed_at'
    ];

    public function sideQuestEvents()
    {
        return $this->hasMany(SideQuestEvent::class);
    }

    public function sideQuest()
    {
        return $this->belongsTo(SideQuest::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
