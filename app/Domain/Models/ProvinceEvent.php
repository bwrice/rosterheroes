<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ProvinceEvents\ProvinceEventBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadEntersProvinceBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadJoinsQuestBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadLeavesProvinceBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadRecruitsHeroBehavior;
use App\Domain\Traits\HasUuid;
use App\Exceptions\UnknownBehaviorException;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProvinceEvent
 * @package App\Domain\Models
 *
 * @property int $id
 * @property string $uuid
 * @property int $province_id
 * @property int|null $squad_id
 * @property string $event_type
 * @property CarbonInterface $happened_at
 * @property array $extra
 *
 * @property Province $province
 * @property Squad|null $squad
 *
 */
class ProvinceEvent extends Model
{
    public const TYPE_SQUAD_ENTERS_PROVINCE = 'squad-enters-province';
    public const TYPE_SQUAD_LEAVES_PROVINCE = 'squad-leaves-province';
    public const TYPE_SQUAD_JOINS_QUEST = 'squad-joins-quest';
    public const TYPE_SQUAD_RECRUITS_HERO = 'squad-recruits-hero';

    public const GLOBAL_EVENTS = [
        self::TYPE_SQUAD_JOINS_QUEST,
        self::TYPE_SQUAD_RECRUITS_HERO
    ];

    use HasFactory;
    use HasUuid;

    protected $casts = [
        'extra' => 'array'
    ];

    protected $dates = [
        'happened_at',
        'created_at',
        'updated_at'
    ];

    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function getBehavior(): ProvinceEventBehavior
    {
        switch ($this->event_type) {
            case self::TYPE_SQUAD_ENTERS_PROVINCE:
                return new SquadEntersProvinceBehavior($this->extra);
            case self::TYPE_SQUAD_LEAVES_PROVINCE:
                return new SquadLeavesProvinceBehavior($this->extra);
            case self::TYPE_SQUAD_JOINS_QUEST:
                return new SquadJoinsQuestBehavior($this->extra);
            case self::TYPE_SQUAD_RECRUITS_HERO:
                return new SquadRecruitsHeroBehavior($this->extra);
        }
        throw new UnknownBehaviorException($this->event_type, ProvinceEventBehavior::class);
    }

    public function getSupplementalResourceData()
    {
        return $this->getBehavior()->getSupplementalResourceData($this);
    }

    public function isGlobalEvent()
    {
        return in_array($this->event_type, self::GLOBAL_EVENTS);
    }
}
