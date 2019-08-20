<?php

namespace App\Policies;

use App\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasurablePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
