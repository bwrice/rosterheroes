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
use Illuminate\Support\Facades\Cache;
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

        return Cache::remember('player_spirits_for_week_' . $week->id, 60 * 60 * 1, function() use ($week) {

            $query = PlayerSpirit::query()->forWeek($week);

            $playerSpirits = $query->with([
                'player.positions'
            ])->orderByDesc('essence_cost')->get();

            return PlayerSpiritResource::collection($playerSpirits);
        });
    }
}
