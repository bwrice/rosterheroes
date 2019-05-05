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
        switch ( $this->abbreviation ) {
            case self::NFL:
                return app(NFLBehavior::class);
            case self::MLB:
                return app(MLBBehavior::class);
            case self::NBA:
                return app(NBABehavior::class);
            case self::NHL:
                return app(NHLBehavior::class);
        }
        throw new \RuntimeException("Couldn't convert league abbreviation into behavior: " . $this->abbreviation );
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
}
