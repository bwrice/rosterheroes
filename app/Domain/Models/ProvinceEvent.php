<?php

namespace App\Domain\Models;

use App\Domain\Models\Json\ProvinceEventData\ProvinceEventData;
use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\Json\ProvinceEventData\SquadLeavesProvince;
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
 * @property ?int $squad_id
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

    use HasFactory;

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

    public function getEventData(): ProvinceEventData
    {
        switch ($this->event_type) {
            case ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE:
                return new SquadEntersProvince($this->province, $this->squad, $this->happened_at, $this->extra);
            case ProvinceEvent::TYPE_SQUAD_LEAVES_PROVINCE:
                return new SquadLeavesProvince($this->province, $this->squad, $this->happened_at, $this->extra);
        }
        throw new \Exception("Unknown event-type: " . $this->event_type . " for Province Event");
    }
}
