<?php

namespace App\Domain\Models;

use App\Domain\Models\Quest;
use App\Domain\Collections\QuestCollection;
use App\Domain\Models\Continent;
use App\StorableEvents\CampaignCreated;
use App\Domain\Models\EventSourcedModel;
use App\Exceptions\InvalidContinentException;
use App\Exceptions\InvalidProvinceException;
use App\Exceptions\MaxQuestsException;
use App\Exceptions\QuestCompletedException;
use App\Exceptions\QuestExistsException;
use App\Exceptions\WeekLockedException;
use App\Domain\Models\Week;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

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
 * @property \App\Domain\Models\Week $week
 * @property Continent $continent
 *
 * @property \App\Domain\Collections\QuestCollection $quests
 * @property Collection $campaignStops
 *
 * @method static Builder forSquadWeek(Squad $squad, Week $week)
 */
class Campaign extends EventSourcedModel
{
    protected $guarded = [];

//    /**
//     * @param array $attributes
//     * @return Campaign|null
//     * @throws \Exception
//     */
//    public static function createWithAttributes(array $attributes)
//    {
//        $uuid = (string) Uuid::uuid4();
//
//        $attributes['uuid'] = $uuid;
//
//        event(new CampaignCreated($attributes));
//
//        return self::uuid($uuid);
//    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function quests()
    {
        return $this->belongsToMany(Quest::class);
    }

    public function campaignStops()
    {
        return $this->hasMany(CampaignStop::class);
    }

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    public function scopeForSquadWeek(Builder $builder, Squad $squad, Week $week)
    {
        return $builder->where('squad_id', '=', $squad->id)->where('week_id', '=', $week->id);
    }

    /**
     * @param Quest $quest
     * @throws MaxQuestsException
     * @throws WeekLockedException
     * @throws QuestCompletedException
     * @throws InvalidProvinceException
     * @throws QuestExistsException
     */
    public function addQuest(Quest $quest)
    {
        if ($quest->isCompleted()) {
            throw new QuestCompletedException($quest);
        } elseif ($this->hasQuest($quest)) {
            throw new QuestExistsException($quest);
        } elseif ($this->continent->id != $quest->province->continent->id){
            throw new InvalidContinentException($this->continent);
        } elseif ( $quest->province->id != $this->squad->province_id ) {
            throw new InvalidProvinceException($this->squad->province);
        } elseif ($this->quests->count() >= $this->squad->getQuestsPerWeekAllowed() ) {
            throw new MaxQuestsException($this->squad->getQuestsPerWeekAllowed());
        } elseif (! Week::current()->adventuringOpen()) {
            throw new WeekLockedException(Week::current());
        }

        $this->quests()->attach($quest->id);
    }

    public function hasQuest(Quest $quest)
    {
        return in_array($quest->id, $this->quests->pluck('id')->toArray());
    }
}
