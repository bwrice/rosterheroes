<?php

namespace App\Policies;

use App\Domain\Models\User;
use App\Domain\Models\Squad;
use Illuminate\Auth\Access\HandlesAuthorization;

class SquadPolicy
{
    public const MANAGE = 'manage';
    public const VIEW = 'view';

    use HandlesAuthorization;

    public function manage(User $user, Squad $squad)
    {
        return $user->id === $squad->user_id;
    }

    public function view(User $user, Squad $squad)
    {
        return $user->id === $squad->user_id;
    }
}
