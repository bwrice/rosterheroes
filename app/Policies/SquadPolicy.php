<?php

namespace App\Policies;

use App\User;
use App\Squad;
use Illuminate\Auth\Access\HandlesAuthorization;

class SquadPolicy
{
    use HandlesAuthorization;


    public function manageSquad(User $user, Squad $squad)
    {
        return $user->id === $squad->user_id;
    }
}
