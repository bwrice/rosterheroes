<?php

use App\Http\Controllers\CombatPositionController;
use App\Http\Controllers\ContinentController;
use App\Http\Controllers\EmptyHeroSlotsController;
use App\Http\Controllers\EquipHeroController;
use App\Http\Controllers\FastTravelController;
use App\Http\Controllers\HeroClassController;
use App\Http\Controllers\HeroChangeCombatPositionController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\HeroRaceController;
use App\Http\Controllers\MobileStorageController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProvinceBorderController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RaiseMeasurableController;
use App\Http\Controllers\RosterHeroesController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\SquadCurrentLocationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TerritoryController;
use App\Http\Controllers\WeekController;
use App\Http\Controllers\SquadBorderTravelController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\SquadHeroRaceController;
use App\Http\Controllers\SquadHeroClassController;
use App\Http\Controllers\SquadHeroController;
use App\Http\Controllers\SquadCampaignController;
use App\Http\Controllers\HeroPlayerSpiritController;
use App\Http\Controllers\CampaignQuestController;
use App\Http\Controllers\WeekGameController;
use App\Http\Controllers\WeekPlayerSpiritController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('weeks')->group(function () {

        /*
         * WEEKS
         *
         * 'api/v1/weeks'
         */

        Route::get('{weekUuid}', [WeekController::class, 'show']);
        Route::get('{weekUuid}/player-spirits', [WeekPlayerSpiritController::class, 'index']);
        Route::get('{weekUuid}/games', [WeekGameController::class, 'index']);
    });

    Route::get('/provinces', [ProvinceController::class, 'index']);
    Route::get('/provinces/{provinceSlug}/borders', [ProvinceBorderController::class, 'index']);
    Route::get('/territories', [TerritoryController::class, 'index']);
    Route::get('/continents', [ContinentController::class, 'index']);

    Route::get('/hero-classes', [HeroClassController::class, 'index']);
    Route::get('/hero-races', [HeroRaceController::class, 'index']);

    Route::get('/combat-positions', [CombatPositionController::class, 'index']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/teams', [TeamController::class, 'index']);
    Route::get('/sports', [SportController::class, 'index']);

    Route::middleware(['auth:api'])->group(function () {

        Route::post('/squads', [SquadController::class, 'store']);
        Route::get('/squads/{squadSlug}', [SquadController::class, 'show']);

        Route::post('/squads/{squadSlug}/fast-travel', FastTravelController::class);

        Route::get('/squads/{squadSlug}/heroes', [SquadHeroController::class, 'index']);
//        Route::get('/squads/{squadSlug}/roster/heroes', RosterHeroesController::class);

        Route::get('/squad/{squadSlug}/hero-classes', SquadHeroClassController::class);
        Route::get('/squad/{squadSlug}/hero-races', SquadHeroRaceController::class);

        Route::get('/squads/{squadSlug}/current-location', SquadCurrentLocationController::class);

        Route::get('/squads/{squadSlug}/mobile-storage', MobileStorageController::class);

        Route::post('/squads/{squadSlug}/border/{borderSlug}', [SquadBorderTravelController::class, 'store']);
        Route::get('/squads/{squadSlug}/border/{borderSlug}', [SquadBorderTravelController::class, 'show']);

        Route::post('/squad/{squadSlug}/heroes', [SquadHeroController::class, 'store']);
        Route::post('/squad/{squadSlug}/campaigns', [SquadCampaignController::class, 'store']);

        Route::prefix('heroes')->group(function () {
            /*
             * HEROES
             *
             * 'api/v1/heroes'
             */

            Route::get('{heroSlug}', [HeroController::class, 'show']);

            Route::post('{heroSlug}/player-spirit', [HeroPlayerSpiritController::class, 'store']);
            Route::delete('{heroSlug}/player-spirit', [HeroPlayerSpiritController::class, 'delete']);

            Route::post('{heroSlug}/empty-slots', EmptyHeroSlotsController::class);

            Route::post('{heroSlug}/equip', EquipHeroController::class);
            Route::post('{heroSlug}/combat-position', HeroChangeCombatPositionController::class);
        });

        Route::post('/campaign/{campaign}/quest/{questUuid}', [CampaignQuestController::class, 'store']);

        Route::prefix('measurables')->group(function () {
            Route::get('/{measurableUuid}/raise', [RaiseMeasurableController::class, 'show']);
            Route::post('/{measurableUuid}/raise', [RaiseMeasurableController::class, 'store']);
        });
    });
});
