<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::middleware('auth')->get('/squads/create', 'SquadController@create')->name('create-squad');
Route::middleware('auth')->get('/command-center/{squadSlug}/{any?}', 'SquadController@show')->where('any', '.*')->name('command-center');

Route::get('/{any}', 'SpaController@index')->where('any', '.*');
