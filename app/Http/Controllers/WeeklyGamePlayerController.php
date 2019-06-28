<?php

namespace App\Http\Controllers;

use App\Domain\Models\WeeklyGamePlayer;
use App\Domain\QueryBuilders\Filters\MaxSalaryFilter;
use App\Domain\QueryBuilders\Filters\MinSalaryFilter;
use App\Domain\QueryBuilders\Filters\PositionFilter;
use App\Http\Resources\WeeklyGamePlayerResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class WeeklyGamePlayerController extends Controller
{
    public function index()
    {
        $query = WeeklyGamePlayer::query();
        $weeklyGamePlayers = QueryBuilder::for($query)
            ->allowedFilters([
                Filter::exact('week', 'week_id'),
                Filter::custom('position', PositionFilter::class),
                Filter::custom('min-salary', MinSalaryFilter::class),
                Filter::custom('max-salary', MaxSalaryFilter::class)
            ])
            ->with([
                'player.positions', 'player.team', 'game.homeTeam', 'game.awayTeam'
            ])->get();
        return WeeklyGamePlayerResource::collection($weeklyGamePlayers);
    }
}
