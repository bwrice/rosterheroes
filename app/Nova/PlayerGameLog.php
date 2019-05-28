<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

/**
 * Class NovaPlayerGameLog
 * @package App\Nova
 *
 * @mixin \App\Domain\Models\PlayerGameLog
 */
class PlayerGameLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Domain\Models\PlayerGameLog::class;

    public function title()
    {
        return $this->getDescription();
    }

    public static $with = [
        'game.homeTeam',
        'game.awayTeam',
        'player',
        'playerStats.statType'
    ];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Player'),
            BelongsTo::make('Game'),
            Number::make('Fantasy Pts', function() {
                return round($this->playerStats->totalPoints(),2);
            }),
            HasMany::make('PlayerStats')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
