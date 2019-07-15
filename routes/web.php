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

use App\Domain\Models\SlotType;
use App\Domain\Slot;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommandCenterController;
use App\Http\Controllers\SquadController;

Route::get('login/google', [LoginController::class, 'redirectToProvider']);
Route::get('login/google/callback', [LoginController::class, 'handleProviderCallback']);

Route::get('/', function () {
    echo phpinfo();
//    return view('welcome');
});

Auth::routes();
Route::get('/squads/create', [SquadController::class, 'create'])->name('create-squad')->middleware('auth');
Route::get('/command-center/{squadSlug}/{any?}', [CommandCenterController::class, 'show'])->middleware('auth')->where('any', '.*')->name('command-center');

//Route::get('/{any}', 'SpaController@index')->where('any', '^(?!nova).*$');
//Route::get('/{any}', 'SpaController@index')->where('any', '.*');
function is_this_slower()
{
    $start = microtime(true);
    $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
    for($i = 1; $i <= 500; $i++) {
        Slot::query()->create([
            'slot_type_id' => $slotType->id,
            'has_slots_type' => 'testing',
            'has_slots_id' => 1
        ]);
    }
    $end = microtime(true);
    return $end - $start;
}

function is_this_faster()
{
    $start = microtime(true);
    $slots = new \Illuminate\Database\Eloquent\Collection();
    $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
    for($i = 1; $i <= 500; $i++) {
        $slots->push(Slot::query()->make([
            'slot_type_id' => $slotType->id,
            'has_slots_type' => 'testing-2',
            'has_slots_id' => 1
        ]));
    }
    Slot::query()->insert($slots->toArray());
    $end = microtime(true);
    return $end - $start;
}