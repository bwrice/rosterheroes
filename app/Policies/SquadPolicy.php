<?php

namespace App\Policies;

use App\User;
use App\Squad;
use Illuminate\Auth\Access\HandlesAuthorization;

class SquadPolicy
{
    use HandlesAuthorization;


    public function adjustSquad(User $user, Squad $squad)
    {
        return $user->id === $squad->user_id;
    }
}
