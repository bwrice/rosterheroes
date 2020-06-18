<?php

namespace App\Policies;

use App\Domain\Models\Shop;
use App\Domain\Models\User;
use App\Domain\Models\Squad;
use Illuminate\Auth\Access\HandlesAuthorization;

class SquadPolicy
{
    public const MANAGE = 'manage';
    public const VIEW = 'view';
    public const VISIT_SHOP = 'visit-shop';

    use HandlesAuthorization;

    public function manage(User $user, Squad $squad)
    {
        return $user->id === $squad->user_id;
    }

    public function view(User $user, Squad $squad)
    {
        return $user->id === $squad->user_id;
    }

    public function visitShop(User $user, Squad $squad, Shop $shop)
    {
        if ($squad->user_id !== $user->id) {
            return false;
        }

        return $squad->province_id === $shop->province_id;
    }
}
