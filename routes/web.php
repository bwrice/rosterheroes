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

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommandCenterController;
use App\Http\Controllers\SquadController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('login/google', [LoginController::class, 'redirectToProvider']);
Route::get('login/google/callback', [LoginController::class, 'handleProviderCallback']);

Route::get('/', function () {
    echo phpinfo();
//    return view('welcome');
});

//Auth::routes();
Route::get('/squads/create', [SquadController::class, 'create'])->name('create-squad')->middleware('auth');
Route::get('/command-center/{squadSlug}/{any?}', [CommandCenterController::class, 'show'])->middleware('auth')->where('any', '.*')->name('command-center');

//Route::get('/{any}', 'SpaController@index')->where('any', '^(?!nova).*$');
//Route::get('/{any}', 'SpaController@index')->where('any', '.*');