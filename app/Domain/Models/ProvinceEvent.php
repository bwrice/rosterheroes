<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\ProvinceEvents\ProvinceEventBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadEntersProvinceBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadJoinsQuestBehavior;
use App\Domain\Behaviors\ProvinceEvents\SquadLeavesProvinceBehavior;
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
            case ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE:
                return new SquadEntersProvinceBehavior($this->extra);
            case ProvinceEvent::TYPE_SQUAD_LEAVES_PROVINCE:
                return new SquadLeavesProvinceBehavior($this->extra);
            case ProvinceEvent::TYPE_SQUAD_JOINS_QUEST:
                return new SquadJoinsQuestBehavior($this->extra);
        }
        throw new UnknownBehaviorException($this->event_type, ProvinceEventBehavior::class);
    }

    public function getSupplementalResourceData()
    {
        return $this->getBehavior()->getSupplementalResourceData($this);
    }
}
