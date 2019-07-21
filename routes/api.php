<?php

use App\Http\Controllers\CurrentWeekController;
use App\Http\Controllers\SquadBorderTravelController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\SquadHeroRaceController;
use App\Http\Controllers\SquadHeroClassController;
use App\Http\Controllers\SquadHeroController;
use App\Http\Controllers\SquadCampaignController;
use App\Http\Controllers\HeroPlayerSpiritController;
use App\Http\Controllers\CampaignQuestController;
use App\Http\Controllers\WeekPlayerSpiritController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {

    Route::get('/weeks/current', CurrentWeekController::class);

    Route::middleware(['auth:api'])->group(function () {

        Route::post('/squads', [SquadController::class, 'store']);
        Route::get('/squads/{squadSlug}', [SquadController::class, 'show']);

        Route::get('/squad/{squadUuid}/hero-classes', SquadHeroClassController::class);
        Route::get('/squad/{squadUuid}/hero-races', SquadHeroRaceController::class);

        Route::post('/squad/{squadUuid}/border/{borderUuid}', [SquadBorderTravelController::class, 'store'])->middleware('auth:api');

        Route::post('/squad/{squadUuid}/heroes', [SquadHeroController::class, 'store']);
        Route::post('/squad/{squadUuid}/campaigns', [SquadCampaignController::class, 'store']);

        Route::post('/hero/{heroUuid}/player-spirit/{playerSpiritUuid}', [HeroPlayerSpiritController::class, 'store']);

        Route::post('/campaign/{campaign}/quest/{questUuid}', [CampaignQuestController::class, 'store']);

        Route::get('/week/{weekUuid}/player-spirits', [WeekPlayerSpiritController::class, 'index']);
    });
});


//Route::get('/teams', 'TeamController@index');