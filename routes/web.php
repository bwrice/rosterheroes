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

use App\Http\Controllers\AttackContentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CommandCenterController;
use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\SyncAttacksController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ContentMiddleware;
use Illuminate\Support\Facades\Route;


/*
 * Landing Page & Dashboard
 */
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

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

Route::get('contact', [ContactSubmissionController::class, 'create']);
Route::post('contact', [ContactSubmissionController::class, 'store']);

/*
 * Terms & Privacy
 */
Route::get('/terms', function () {
    return view('terms');
});
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/faq', [PagesController::class, 'faq'])->name('faq');


Route::get('/squads/create', [SquadController::class, 'create'])->name('create-squad');

Route::get('/command-center/{squadSlug}/{subPage?}', [CommandCenterController::class, 'show'])->where('subPage', '.*')->name('command-center');

Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {

    Route::prefix('content')->group(function () {

        Route::get('/', [ContentController::class, 'show']);

        Route::prefix('attacks')->group(function () {

            Route::get('/', [AttackContentController::class, 'index']);
            Route::get('/create', [AttackContentController::class, 'create']);

            Route::post('/sync', SyncAttacksController::class);

            Route::middleware([ContentMiddleware::class])->group(function () {

                Route::get('/create', [AttackContentController::class, 'create']);
                Route::post('/', [AttackContentController::class, 'store']);
                Route::get('/{attackUuid}/edit', [AttackContentController::class, 'edit']);
                Route::put('/{attackUuid}', [AttackContentController::class, 'update']);
            });
        });
    });
});
