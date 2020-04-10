<?php

namespace App\Http\Controllers;

use App\Domain\Models\Week;
use App\Domain\Models\PlayerSpirit;
use App\Http\Resources\PlayerSpiritResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;

class WeekPlayerSpiritController extends Controller
{
    public function index($weekUuid, Request $request)
    {
        if ('current' === $weekUuid) {
            $week = Week::current();
        } else {
            $week = Week::findUuidOrFail($weekUuid);
        }
        $offset = $request->offset;
        $limit = $request->limit;
        $query = PlayerSpirit::query()->forWeek($week);

        $playerSpirits = $query->with([
            'playerGameLog.player.positions',
        ])->orderByDesc('essence_cost')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return PlayerSpiritResource::collection($playerSpirits);

//        return Cache::remember('player_spirits_for_week_' . $week->id, 60 * 60 * 1, function() use ($week) {
//
//            $query = PlayerSpirit::query()->forWeek($week);
//
//            $playerSpirits = $query->with([
//                'playerGameLog.player.positions',
//            ])->orderByDesc('essence_cost')->get();
//
//            return PlayerSpiritResource::collection($playerSpirits);
//        });
    }
}
