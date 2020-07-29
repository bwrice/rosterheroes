<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Leagues\LeagueBehavior;
use App\Domain\Collections\TeamCollection;
use App\Domain\Models\Team;
use App\Domain\Models\Sport;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sport
 * @package App
 *
 * @property int $id
 * @property int $sport_id
 * @property string $abbreviation
 *
 * @property TeamCollection $teams
 * @property Sport $sport
 *
 * @method static Builder abbreviation(array $abbreviations)
 */
class League extends Model
{
    const NFL = 'NFL';
    const MLB = 'MLB';
    const NBA = 'NBA';
    const NHL = 'NHL';

    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function getBehavior(): LeagueBehavior
    {
        switch ($this->abbreviation) {
            case self::NFL:
                return new LeagueBehavior(self::NFL, 225, 50, 32);
            case self::MLB:
                return new LeagueBehavior(self::MLB, 70, 290, 30);
            case self::NBA:
                return new LeagueBehavior(self::NBA, 260, 180, 30);
            case self::NHL:
                return new LeagueBehavior(self::NHL, 250, 170, 31);
        }
        throw new UnknownBehaviorException($this->abbreviation, LeagueBehavior::class);
    }

    /**
     * @return League
     */
    public static function nfl()
    {
        /** @var League $league */
        $league = self::query()->where('abbreviation', '=', self::NFL)->first();
        return $league;
    }

    /**
     * @return League
     */
    public static function mlb()
    {
        /** @var League $league */
        $league = self::query()->where('abbreviation', '=', self::MLB)->first();
        return $league;
    }

    /**
     * @return League
     */
    public static function nba()
    {
        /** @var League $league */
        $league = self::query()->where('abbreviation', '=', self::NBA)->first();
        return $league;
    }

    /**
     * @return League
     */
    public static function nhl()
    {
        /** @var League $league */
        $league = self::query()->where('abbreviation', '=', self::NHL)->first();
        return $league;
    }

    public function scopeAbbreviation(Builder $builder, array $abbreviations)
    {
        return $builder->whereIn('abbreviation', $abbreviations);
    }
}
