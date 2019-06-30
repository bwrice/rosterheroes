<?php

namespace App\Http\Controllers;

use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use App\Domain\QueryBuilders\Filters\MaxSalaryFilter;
use App\Domain\QueryBuilders\Filters\MinSalaryFilter;
use App\Domain\QueryBuilders\Filters\PositionFilter;
use App\Http\Resources\PlayerSpiritResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class WeekPlayerSpiritController extends Controller
{
    public function index($weekUuid)
    {
        $week = Week::uuidOrFail($weekUuid);
        $query = PlayerSpirit::query()->forWeek($week);
        $playerSpirits = QueryBuilder::for($query)
            ->allowedFilters([
                Filter::custom('position', PositionFilter::class),
                Filter::custom('min-salary', MinSalaryFilter::class),
                Filter::custom('max-salary', MaxSalaryFilter::class)
            ])
            ->with([
                'player.positions', 'player.team', 'game.homeTeam', 'game.awayTeam'
            ])->get();
        return PlayerSpiritResource::collection($playerSpirits);
    }
}
