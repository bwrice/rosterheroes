<?php

namespace App\Domain\Models;

use App\Domain\Collections\SideQuestCollection;
use App\Domain\QueryBuilders\CampaignStopQueryBuilder;
use Illuminate\Database\Eloquent\Collection;

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
 * @property SideQuestCollection $sideQuests
 * @property Collection $sideQuestResults
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

    public function sideQuests()
    {
        return $this->belongsToMany(SideQuest::class, 'side_quest_results')->withTimestamps();
    }

    public function sideQuestResults()
    {
        return $this->hasMany(SideQuestResult::class);
    }

    public function newEloquentBuilder($query)
    {
        return new CampaignStopQueryBuilder($query);
    }

    public static function historicResourceRelations()
    {
        return [
            'province',
            'sideQuestResults.sideQuestSnapshot.minionSnapshots.attackSnapshots',
        ];
    }

}
