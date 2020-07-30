<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Actions\CreateUserAction;
use App\Http\Controllers\Controller;
use App\Domain\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param CreateUserAction $createUserAction
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(CreateUserAction $createUserAction)
    {
        /** @var \Laravel\Socialite\Two\User $googleUser */
        $googleUser = Socialite::driver('google')->user();

        if ($googleUser) {

            $user = User::query()->where('email', '=', $googleUser->email)->first();

            if (! $user) {
                $user = $createUserAction->execute($googleUser->email, $googleUser->name);
                $user->markEmailAsVerified();
                event(new Verified($user));
            }
            Auth::login($user);
            return redirect('/');
        }

        return redirect('/');
    }
}
