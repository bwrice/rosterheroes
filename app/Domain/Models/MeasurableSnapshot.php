<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MeasurableSnapshot
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $measurable_id
 * @property int $hero_snapshot_id
 * @property int $pre_buffed_amount
 * @property int $buffed_amount
 * @property int $final_amount
 *
 * @property Measurable $measurable
 * @property HeroSnapshot $heroSnapshot
 */
class MeasurableSnapshot extends Model
{
    protected $guarded = [];

    public function measurable()
    {
        return $this->belongsTo(Measurable::class);
    }

    public function heroSnapshot()
    {
        return $this->belongsTo(HeroSnapshot::class);
    }
}
