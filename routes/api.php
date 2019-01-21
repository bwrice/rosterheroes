<?php

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/squads', 'SquadController@store');

Route::middleware('auth:api')->post('/squad/{squadUuid}/border/{borderUuid}', 'SquadBorderTravelController@store');

Route::middleware('auth:api')->post('/squad/{squadUuid}/heroes', 'SquadHeroController@store');

Route::middleware('auth:api')->post('/hero/{heroUuid}/player-week/{gamePlayerUuid}', 'HeroGamePlayerController@store');