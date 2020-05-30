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

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CommandCenterController;
use App\Http\Controllers\SquadController;

Route::get('/terms', function () {
    return view('terms');
});

/*
 * Register
 */
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

/*
 * Login
 */
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('login/google', [LoginController::class, 'redirectToProvider']);
Route::get('login/google/callback', [LoginController::class, 'handleProviderCallback']);

/*
 * Email Verification
 */

Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');


/*
 * Password Reset
 */
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

Route::get('/squads/create', [SquadController::class, 'create'])->name('create-squad');

Route::get('/command-center/{squadSlug}/{subPage?}', [CommandCenterController::class, 'show'])->where('subPage', '.*')->name('command-center');
