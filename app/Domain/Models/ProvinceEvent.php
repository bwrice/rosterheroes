<?php

namespace App\Domain\Models;

use App\Domain\Models\Casts\CastsProvinceEventData;
use App\Domain\Models\Json\ProvinceEventData\ProvinceEventData;
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
 * @property string $event_type
 * @property CarbonInterface $happened_at
 * @property ProvinceEventData $data
 *
 * @property Province $province
 *
 */
class ProvinceEvent extends Model
{
    public const TYPE_SQUAD_ENTERS_PROVINCE = 'squad-enters-province';

    use HasFactory;

    protected $casts = [
        'data' => CastsProvinceEventData::class
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
}
