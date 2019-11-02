<?php

namespace App\Policies;

use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasurablePolicy
{
    public const RAISE = 'raise';
    /**
     * @var SquadPolicy
     */
    private $squadPolicy;

    use HandlesAuthorization;

    public function __construct(SquadPolicy $squadPolicy)
    {
        $this->squadPolicy = $squadPolicy;
    }

    public function raise(User $user, Measurable $measurable)
    {
        return $this->squadPolicy->manage($user, $measurable->hero->squad);
    }
}
