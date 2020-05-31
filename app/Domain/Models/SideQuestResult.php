<?php

namespace App\Domain\Models;

use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestEvent;
use App\Domain\QueryBuilders\SideQuestEventQueryBuilder;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use PhpParser\Node\Expr\AssignOp\Mod;

/**
 * Class SideQuestResult
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $campaign_stop_id
 * @property int $side_quest_id
 * @property CarbonInterface|null $combat_processed_at
 * @property CarbonInterface|null $rewards_processed_at
 * @property CarbonInterface|null $side_effects_processed_at
 * @property int|null $experience_rewarded
 * @property int|null $favor_rewarded
 *
 * @property SideQuest $sideQuest
 * @property CampaignStop $campaignStop
 *
 * @property Collection $sideQuestEvents
 */
class SideQuestResult extends Model
{
    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'combat_processed_at',
        'rewards_processed_at',
        'side_effects_processed_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|SideQuestEventQueryBuilder
     */
    public function sideQuestEvents()
    {
        return $this->hasMany(SideQuestEvent::class);
    }

    public function sideQuest()
    {
        return $this->belongsTo(SideQuest::class);
    }

    public function campaignStop()
    {
        return $this->belongsTo(CampaignStop::class);
    }
}