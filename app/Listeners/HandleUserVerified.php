<?php

namespace App\Listeners;

use App\Domain\Models\User;
use App\Facades\Admin;
use App\Notifications\NewVerifiedUserNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleUserVerified implements ShouldQueue
{

    /**
     * @param Verified $verifiedEvent
     */
    public function handle(Verified $verifiedEvent)
    {
        /** @var User $user */
        $user = $verifiedEvent->user;
        Admin::notify(new NewVerifiedUserNotification($user));
    }
}
