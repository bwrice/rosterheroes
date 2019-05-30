<?php

namespace App\Domain\Models;

use App\Domain\Collections\GameCollection;
use App\Domain\Models\Game;
use App\Domain\Collections\WeekCollection;
use App\Domain\QueryBuilders\WeekQueryBuilder;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * Class Week
 * @package App
 *
 * @property int $id
 * @property string $name
 * @property CarbonImmutable $proposals_scheduled_to_lock_at
 * @property CarbonImmutable $diplomacy_scheduled_to_lock_at
 * @property CarbonImmutable $everything_locks_at
 * @property CarbonImmutable $ends_at
 * @property CarbonImmutable|null $weekly_game_players_queued_at
 * @property CarbonImmutable|null $proposals_processed_at
 * @property CarbonImmutable|null $diplomacy_processed_at
 * @property CarbonImmutable|null $finalized_at
 *
 * @method static WeekQueryBuilder query()
 */
class Week extends Model
{
    protected static $current = null;

    protected static $testCurrent = null;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'weekly_game_players_queued_at',
        'proposals_scheduled_to_lock_at',
        'proposals_processed_at',
        'diplomacy_scheduled_to_lock_at',
        'diplomacy_processed_at',
        'everything_locks_at',
        'ends_at',
        'finalized_at'
    ];

    public function newCollection(array $models = [])
    {
        return new WeekCollection($models);
    }

    public function newEloquentBuilder($query)
    {
        return new WeekQueryBuilder($query);
    }

    /**
     * @return Week
     */
    public static function current()
    {
        return self::$testCurrent ?: self::$current;
    }

    public static function setCurrent(Week $week = null)
    {
        self::$current = $week;
    }

    public static function setTestCurrent(Week $week = null)
    {
        self::$testCurrent = $week;
    }

    public static function isCurrent(Week $week)
    {
        return self::current()->id == $week->id;
    }

    public function games()
    {
        return $this->hasMany(Game::class);
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

    public function gamePlayersQueued()
    {
        return $this->weekly_game_players_queued_at !== null;
    }

    public function getGamesPeriod()
    {
        return CarbonPeriod::create($this->everything_locks_at, $this->ends_at);
    }

    public function getValidGames()
    {
        /** @var GameCollection $games */
        $games = Game::query()->withinPeriod($this->getGamesPeriod())->get();
        return $games;
    }
}
