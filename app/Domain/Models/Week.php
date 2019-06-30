<?php

namespace App\Domain\Models;

use App\Domain\Collections\GameCollection;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Models\Game;
use App\Domain\Collections\WeekCollection;
use App\Domain\QueryBuilders\WeekQueryBuilder;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Class Week
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property CarbonImmutable $proposals_scheduled_to_lock_at
 * @property CarbonImmutable $diplomacy_scheduled_to_lock_at
 * @property CarbonImmutable $everything_locks_at
 * @property CarbonImmutable $ends_at
 * @property CarbonImmutable|null $player_spirits_queued_at
 * @property CarbonImmutable|null $proposals_processed_at
 * @property CarbonImmutable|null $diplomacy_processed_at
 * @property CarbonImmutable|null $finalized_at
 *
 * @property PlayerSpiritCollection $playerSpirits
 * @property GameCollection $games
 *
 * @method static WeekQueryBuilder query()
 */
class Week extends EventSourcedModel
{
    protected static $current = null;

    protected static $testCurrent = null;

    protected static $testLatest = null;

    protected static $useNullTestLatest = false;

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'player_spirits_queued_at',
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

    public static function setTestLatest(Week $week = null)
    {
        self::$testLatest = $week;
    }

    public static function useNullTestLatest()
    {
        self::$useNullTestLatest = true;
    }

    public static function isCurrent(Week $week)
    {
        return self::current()->id == $week->id;
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function playerSpirits()
    {
        return $this->hasMany(PlayerSpirit::class);
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
        return $this->player_spirits_queued_at !== null;
    }

    /**
     * @return CarbonPeriod
     */
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

    public static function getLatest(): ?Week
    {
        if (self::$useNullTestLatest) {
            return null;
        } elseif (self::$testLatest) {
            return self::$testLatest;
        }
        return self::query()->orderByDesc('ends_at')->first();
    }

    /**
     * @return Week
     */
    public static function makeForNow()
    {
        $start = Date::now()->next(3);

        $wednesday = $start->copy()->setTimezone('America/New_York');
        $offset = $wednesday->getOffset();
        $wednesday = $wednesday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

        $friday = $start->copy()->addDays(2)->setTimezone('America/New_York');
        $offset = $friday->getOffset();
        $friday = $friday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

        $sunday = $start->copy()->addDays(4)->setTimezone('America/New_York');
        $offset = $sunday->getOffset();
        $sunday = $sunday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

        $monday = $start->copy()->addDays(5)->setTimezone('America/New_York');
        $offset = $monday->getOffset();
        $monday = $monday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

        return self::make([
            'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
            'proposals_scheduled_to_lock_at' => $wednesday,
            'diplomacy_scheduled_to_lock_at' => $friday,
            'everything_locks_at' => $sunday,
            'ends_at' => $monday
        ]);
    }
}
