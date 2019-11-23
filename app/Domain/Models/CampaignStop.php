<?php

namespace App\Domain\Models;

use App\Aggregates\CampaignStopAggregate;
use App\Domain\Collections\SkirmishCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignStop
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property int $province_id
 * @property int $campaign_id
 * @property int $quest_id
 *
 * @property Campaign $campaign
 * @property Quest $quest
 * @property Province $province
 *
 * @property SkirmishCollection $skirmishes
 */
class CampaignStop extends EventSourcedModel
{

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

    public function getAggregate()
    {
        /** @var CampaignStopAggregate $aggregate */
        $aggregate = CampaignStopAggregate::retrieve($this->uuid);
        return $aggregate;
    }
}
