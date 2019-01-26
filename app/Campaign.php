<?php

namespace App;

use App\Campaigns\Quests\Quest;
use App\Campaigns\Quests\QuestCollection;
use App\Events\CampaignCreationRequested;
use App\Exceptions\InvalidContinentException;
use App\Exceptions\InvalidProvinceException;
use App\Exceptions\MaxQuestsException;
use App\Exceptions\QuestCompletedException;
use App\Exceptions\QuestExistsException;
use App\Exceptions\WeekLockedException;
use App\Weeks\Week;
use Illuminate\Database\Eloquent\Builder;
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
 * @property Week $week
 * @property Continent $continent
 *
 * @property QuestCollection $quests
 *
 * @method static Builder squadThisWeek(int $squadID)
 */
class Campaign extends EventSourcedModel
{
    protected $guarded = [];

    /**
     * @param array $attributes
     * @return Campaign|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new CampaignCreationRequested($attributes));

        return self::uuid($uuid);
    }

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

    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    public function scopeSquadThisWeek(Builder $builder, int $squadID)
    {
        return $builder->where('squad_id', '=', $squadID)->where('week_id', '=', Week::current()->id);
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
