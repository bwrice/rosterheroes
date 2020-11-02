<?php

namespace App\Domain\Models;

use App\Domain\QueryBuilders\CampaignQueryBuilder;
use App\Domain\QueryBuilders\CampaignStopQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Campaign
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $week_id
 * @property int $squad_id
 * @property int $continent_id
 *
 * @property Squad $squad
 * @property Week $week
 * @property Continent $continent
 *
 * @property Collection $campaignStops
 *
 * @method static Builder forSquadWeek(Squad $squad, Week $week)
 *
 * @method static CampaignQueryBuilder query()
 */
class Campaign extends EventSourcedModel
{
    protected $guarded = [];

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    /**
     * @return HasMany|CampaignStopQueryBuilder
     */
    public function campaignStops()
    {
        return $this->hasMany(CampaignStop::class);
    }

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    public function newEloquentBuilder($query)
    {
        return new CampaignQueryBuilder($query);
    }

    public static function campaignResourceRelations()
    {
        return [
            'campaignStops.quest',
            'campaignStops.sideQuests'
        ];
    }

    public static function historyResourceRelations()
    {
        return [
            'week',
            'campaignStops.quest',
            'campaignStops.sideQuestResults.sideQuest.minions.enemyType',
            'campaignStops.sideQuestResults.sideQuest.minions.combatPosition',
            'campaignStops.sideQuestResults.sideQuest.minions.attacks.attackerPosition',
            'campaignStops.sideQuestResults.sideQuest.minions.attacks.targetPosition',
            'campaignStops.sideQuestResults.sideQuest.minions.attacks.targetPriority',
            'campaignStops.sideQuestResults.sideQuest.minions.attacks.damageType'
        ];
    }

    public function getDescription()
    {
        $adventuringLocksAt = $this->week->adventuring_locks_at;
        return $adventuringLocksAt->subDays(6)->format('m-d-y') . ' to ' . $adventuringLocksAt->format('m-d-y');
    }
}
