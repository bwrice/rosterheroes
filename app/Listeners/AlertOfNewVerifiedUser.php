<?php

namespace App\Listeners;

use App\Domain\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AlertOfNewVerifiedUser implements ShouldQueue
{

    /**
     * @param Verified $verifiedEvent
     */
    public function handle(Verified $verifiedEvent)
    {
        /** @var User $user */
        $user = $verifiedEvent->user;
        Log::alert("New user verified!", [
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
