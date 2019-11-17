<?php

namespace App\Domain\Models;

use App\Domain\Collections\SkirmishCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignStop
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 *
 * @property Campaign $campaign
 * @property Quest $quest
 * @property Province $province
 *
 * @property SkirmishCollection $skirmishes
 */
class CampaignStop extends Model
{
    protected $guarded = [];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function skirmishes()
    {
        return $this->belongsToMany(Skirmish::class, 'campaign_stop_skirmish', 'stop_id')->withTimestamps();
    }
}
