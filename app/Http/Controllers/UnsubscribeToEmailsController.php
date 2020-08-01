<?php

namespace App\Http\Controllers;

use App\Domain\Models\EmailSubscription;
use App\Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnsubscribeToEmailsController extends Controller
{
    public function __invoke($user, $emailSubscription)
    {
        /** @var User $user */
        $user = User::findUuidOrFail($user);

        $email = $user->email;

        if ($emailSubscription === 'all') {
            $user->emailSubscriptions()->sync([]);
            $subName = 'all';
        } else {
            /** @var EmailSubscription $emailSubscription */
            $emailSubscription = EmailSubscription::query()->findOrFail($emailSubscription);
            $user->emailSubscriptions()->detach([$emailSubscription->id]);
            $subName = str_replace('-', ' ', $emailSubscription->name);
        }

        $loggedInUser = Auth::user();

        return view('account.emails.unsubscribe', [
            'email' => $email,
            'subName' => $subName,
            'linkText' => $loggedInUser ? 'Home' : 'Log In',
            'linkHref' => $loggedInUser ? '/dashboard' : '/login'
        ]);
    }
}
