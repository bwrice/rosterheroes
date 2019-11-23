<?php

use App\Http\Controllers\CampaignStopSkirmishController;
use App\Http\Controllers\CombatPositionController;
use App\Http\Controllers\ContinentController;
use App\Http\Controllers\CurrentCampaignController;
use App\Http\Controllers\CurrentLocationQuestsController;
use App\Http\Controllers\EnlistForQuestController;
use App\Http\Controllers\UnEquipHeroController;
use App\Http\Controllers\EquipHeroController;
use App\Http\Controllers\FastTravelController;
use App\Http\Controllers\HeroClassController;
use App\Http\Controllers\HeroChangeCombatPositionController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\HeroRaceController;
use App\Http\Controllers\CastSpellController;
use App\Http\Controllers\MeasurableTypeController;
use App\Http\Controllers\MobileStorageController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProvinceBorderController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RaiseHeroMeasurableController;
use App\Http\Controllers\RemoveSpellController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\CurrentLocationProvinceController;
use App\Http\Controllers\SquadSpellController;
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

    Route::prefix('provinces')->group(function () {

        /*
         * PROVINCES
         *
         * 'api/v1/provinces'
         */
        Route::get('/', [ProvinceController::class, 'index']);
        Route::get('/{provinceSlug}/borders', [ProvinceBorderController::class, 'index']);
    });

    Route::get('/territories', [TerritoryController::class, 'index']);
    Route::get('/continents', [ContinentController::class, 'index']);

    Route::get('/hero-classes', [HeroClassController::class, 'index']);
    Route::get('/hero-races', [HeroRaceController::class, 'index']);
    Route::get('/measurable-types', [MeasurableTypeController::class, 'index']);

    Route::get('/combat-positions', [CombatPositionController::class, 'index']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/teams', [TeamController::class, 'index']);
    Route::get('/sports', [SportController::class, 'index']);

    Route::middleware(['auth:api'])->group(function () {

        Route::prefix('squads')->group(function() {

            Route::post('/', [SquadController::class, 'store']);
            Route::get('{squadSlug}', [SquadController::class, 'show']);

            Route::post('{squadSlug}/fast-travel', FastTravelController::class);

            Route::get('{squadSlug}/heroes', [SquadHeroController::class, 'index']);
            Route::get('{squadSlug}/mobile-storage', MobileStorageController::class);

            Route::post('{squadSlug}/border-travel/{borderSlug}', [SquadBorderTravelController::class, 'store']);
            Route::get('{squadSlug}/border-travel/{borderSlug}', [SquadBorderTravelController::class, 'show']);

            Route::get('{squadSlug}/spells', [SquadSpellController::class, 'index']);

            /*
             * CURRENT LOCATION
             */
            Route::prefix('{squadSlug}/current-location')->group(function () {

                Route::get('province', CurrentLocationProvinceController::class);
                Route::get('quests', CurrentLocationQuestsController::class);
            });

            /*
             * CURRENT CAMPAIGN
             */
            Route::get('{squadSlug}/current-campaign', CurrentCampaignController::class);

            Route::post('{squadSlug}/enlist', EnlistForQuestController::class);
        });

        Route::get('/squad/{squadSlug}/hero-classes', SquadHeroClassController::class);
        Route::get('/squad/{squadSlug}/hero-races', SquadHeroRaceController::class);

        Route::post('/squad/{squadSlug}/heroes', [SquadHeroController::class, 'store']);

        Route::prefix('heroes')->group(function () {
            /*
             * HEROES
             *
             * 'api/v1/heroes'
             */

            Route::get('{heroSlug}', [HeroController::class, 'show']);

            Route::post('{heroSlug}/player-spirit', [HeroPlayerSpiritController::class, 'store']);
            Route::delete('{heroSlug}/player-spirit/{spiritUuid}', [HeroPlayerSpiritController::class, 'delete']);

            Route::post('{heroSlug}/unequip', UnEquipHeroController::class);

            Route::post('{heroSlug}/equip', EquipHeroController::class);
            Route::post('{heroSlug}/combat-position', HeroChangeCombatPositionController::class);

            Route::get('{heroSlug}/raise-measurable', [RaiseHeroMeasurableController::class, 'show']);
            Route::post('{heroSlug}/raise-measurable', [RaiseHeroMeasurableController::class, 'store']);

            Route::post('{heroSlug}/cast-spell', CastSpellController::class);
            Route::post('{heroSlug}/remove-spell', RemoveSpellController::class);
        });

        Route::prefix('campaign-stops')->group(function () {
            Route::post('{stopUuid}/skirmishes', [CampaignStopSkirmishController::class, 'store']);
            Route::delete('{stopUuid}/skirmishes', [CampaignStopSkirmishController::class, 'delete']);
        });
    });
});
