<?php

namespace App\Http\Controllers;

use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use App\Domain\QueryBuilders\Filters\HeroRaceFilter;
use App\Domain\QueryBuilders\Filters\MaxEssenceCostFilter;
use App\Domain\QueryBuilders\Filters\MinEssenceCostFilter;
use App\Domain\QueryBuilders\Filters\PositionFilter;
use App\Http\Resources\PlayerSpiritResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class WeekPlayerSpiritController extends Controller
{
    public function index($weekUuid)
    {
        if ('current' === $weekUuid) {
            $week = Week::current();
        } else {
            $week = Week::findUuidOrFail($weekUuid);
        }

        $query = PlayerSpirit::query()->forWeek($week);
        $playerSpirits = QueryBuilder::for($query)
            ->allowedFilters([
                Filter::custom('position', PositionFilter::class),
                Filter::custom('hero-race', HeroRaceFilter::class),
                Filter::custom('min-essence-cost', MinEssenceCostFilter::class),
                Filter::custom('max-essence-cost', MaxEssenceCostFilter::class)
            ])
            ->with([
                'player.positions', 'player.team', 'game.homeTeam', 'game.awayTeam'
            ])->orderByDesc('essence_cost')->get();
        return PlayerSpiritResource::collection($playerSpirits);
    }
}
