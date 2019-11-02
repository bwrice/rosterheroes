<?php

namespace App\Policies;

use App\Domain\Models\Hero;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HeroPolicy
{
    use HandlesAuthorization;

    public const MANAGE = 'manage';

    /**
     * @var SquadPolicy
     */
    private $squadPolicy;

    public function __construct(SquadPolicy $squadPolicy)
    {
        $this->squadPolicy = $squadPolicy;
    }

    public function manage(User $user, Hero $hero)
    {
        return $this->squadPolicy->manage($user, $hero->squad);
    }
}
