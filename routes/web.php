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
use App\Domain\Models\Game;
use App\Facades\CurrentWeek;
use App\Http\Controllers\ItemTypeContentController;
use App\Http\Controllers\UnsubscribeToEmailsController;
use Illuminate\Support\Facades\Route;
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
 * Email Subscriptions
 */
Route::get('unsubscribe/{user}/emails/{emailSubscription}', UnsubscribeToEmailsController::class)
    ->name('emails.unsubscribe')
    ->middleware('signed');

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

        Route::prefix('item-types')->group(function () {

            Route::get('/', [ItemTypeContentController::class, 'index']);
            Route::get('/{itemTypeUuid}/edit', [ItemTypeContentController::class, 'edit']);
            Route::put('/{itemTypeUuid}', [ItemTypeContentController::class, 'update']);
        });
    });
});

if (! function_exists('myHelper')) {
    function myHelper() {
        $currentWeekID = \App\Facades\CurrentWeek::id();
        $validPeriodForWeek = CurrentWeek::validGamePeriod();

        $games = \App\Domain\Models\Game::query()->whereHas('playerGameLogs', function (\Illuminate\Database\Eloquent\Builder $builder) use ($currentWeekID) {
            $builder->whereHas('playerSpirit', function (\Illuminate\Database\Eloquent\Builder $builder) use ($currentWeekID) {
                $builder->where('week_id', '=', $currentWeekID);
            });
        })->where(function (\Illuminate\Database\Eloquent\Builder $builder) use ($validPeriodForWeek) {
            $builder->where('starts_at', '<', $validPeriodForWeek->getStartDate())->orWhere('starts_at', '>', $validPeriodForWeek->getEndDate());
        })->get();

        return $games;
    }
}

if (! function_exists('myHelper2')) {
    function myHelper2() {

        $missing = collect();

        /** @var \App\External\Stats\StatsIntegration $statsIntegration */
        $statsIntegration = app(\App\External\Stats\StatsIntegration::class);

        $games = Game::query()->with(['homeTeam', 'externalGames'])->where('starts_at', '>', now()->subDays(7))->where('starts_at', '<' , now()->addDays(2))->get();

        $games->groupBy(function (Game $game) {
            return $game->homeTeam->league_id;
        })->each(function (\Illuminate\Database\Eloquent\Collection $groupedByLeague, $leagueID) use ($statsIntegration, &$missing) {

            $league = \App\Domain\Models\League::query()->find($leagueID);

            $groupedByLeague->groupBy(function (Game $game) {
                return $game->season_type;
            })->each(function (\Illuminate\Support\Collection $groupedBySeasonTypeGames, $seasonType) use ($league, $statsIntegration, &$missing) {

                $regularSeason = $seasonType === Game::SEASON_TYPE_REGULAR;

                $gameDTOS = $statsIntegration->getGameDTOs($league, 0, $regularSeason);

                $missing = $missing->merge($groupedBySeasonTypeGames->filter(function (Game $game) use ($gameDTOS) {

                    $match = $gameDTOS->first(function (\App\Domain\DataTransferObjects\GameDTO $gameDTO) use ($game) {
                        $matchingExternalGame = $game->externalGames->first(function (\App\Domain\Models\ExternalGame $externalGame) use ($gameDTO) {
                            return $externalGame->external_id === $gameDTO->getExternalID();
                        });

                        return ! is_null($matchingExternalGame);
                    });

                    return is_null($match);
                }));
            });
        });

        return $missing;
    }
}
