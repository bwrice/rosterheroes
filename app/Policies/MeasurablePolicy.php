<?php

namespace App\Policies;

use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasurablePolicy
{
    public const RAISE = 'raise';

    use HandlesAuthorization;

    public function raise(User $user, Measurable $measurable)
    {
        $hasMeasurables = $measurable->hasMeasurables;
        if ($hasMeasurables instanceof Hero) {
            $squad = $hasMeasurables->getSquad();
            if (! $squad) {
                // TODO: potential to raise measurables for heroes left at homes and not attached to squad's hero post
                return false;
            }

            return $squad->user_id === $user->id;
        }
        // TODO: admin capabilities
        return false;
    }
}
