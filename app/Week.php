<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Week
 * @package App
 *
 * @property int $id
 * @property Carbon $proposals_scheduled_to_lock_at
 * @property Carbon $diplomacy_scheduled_to_lock_at
 * @property Carbon $everything_locks_at
 * @property Carbon $ends_at
 * @property Carbon|null $proposals_processed_at
 * @property Carbon|null $diplomacy_processed_at
 * @property Carbon|null $finalized_at
 */
class Week extends Model
{
    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'proposals_scheduled_to_lock_at',
        'proposals_processed_at',
        'diplomacy_scheduled_to_lock_at',
        'diplomacy_processed_at',
        'everything_locks_at',
        'ends_at',
        'finalized_at'
    ];

    public static function current()
    {
        return self::query()->orderBy('ends_at')->whereNull('finalized_at')->first();
    }

    /**
     * @return static|null
     */
    public function getPreviousWeek()
    {
        /** @var static $previousWeek */
        $previousWeek = self::query()->orderBy('ends_at')->where('ends_at', '<=', $this->ends_at )->first();
        return $previousWeek;
    }

    public function proposalsOpen()
    {
        if ($this->everything_locks_at->isPast()) {
            return false;
        }

        $previousWeek = $this->getPreviousWeek();

        if( ! $previousWeek) {
            return $this->proposals_scheduled_to_lock_at->isFuture();
        }

        if ($previousWeek->finalized_at) {
            return $previousWeek->finalized_at->copy()->addHours(12)->isFuture() || $this->proposals_scheduled_to_lock_at->isFuture();

        } else {
            return false;
        }
    }

    public function diplomacyOpen()
    {
        if ($this->everything_locks_at->isPast() || ! $this->proposals_processed_at ) {
            return false;
        }

        return $this->proposals_processed_at->copy()->addHours(12)->isFuture() || $this->diplomacy_scheduled_to_lock_at->isFuture();
    }

    public function adventuringOpen()
    {
        return $this->everything_locks_at->isFuture();
    }
}
