<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\Leagues\LeagueBehavior;
use App\Domain\Behaviors\Leagues\MLBBehavior;
use App\Domain\Behaviors\Leagues\NBABehavior;
use App\Domain\Behaviors\Leagues\NFLBehavior;
use App\Domain\Behaviors\Leagues\NHLBehavior;
use App\Domain\Collections\LeagueCollection;
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

    public function newCollection(array $models = [])
    {
        return new LeagueCollection($models);
    }

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
                return new LeagueBehavior(self::NFL, 240, 50);
            case self::MLB:
                return new LeagueBehavior(self::MLB, 80, 290);
            case self::NBA:
                return new LeagueBehavior(self::NBA, 275, 180);
            case self::NHL:
                return new LeagueBehavior(self::NHL, 265, 170);
        }
        throw new UnknownBehaviorException($this->abbreviation, LeagueBehavior::class);
    }

    public function isLive()
    {
        return $this->getBehavior()->isLive();
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

    /**
     * @param bool $live
     * @return LeagueCollection
     */
    public static function live(bool $live = true): LeagueCollection
    {
        /** @var LeagueCollection $leagues */
        $leagues = self::all()->filter(function (League $league) use ($live) {
            return $live ? $league->isLive() : ! $league->isLive();
        });
        return $leagues;
    }
}
