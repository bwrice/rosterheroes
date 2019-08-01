<?php

namespace App\Aggregates;

use App\StorableEvents\UserCreated;
use Illuminate\Support\Facades\Hash;
use Spatie\EventProjector\AggregateRoot;

final class UserAggregate extends AggregateRoot
{
    public function createUser(string $email, string $name, string $password = null, string $emailVerifiedAt = null)
    {
        if ($password) {
            // Hash now so we don't have the plain text password in the stored events table
            $password = Hash::make($password);
        }
        $this->recordThat(new UserCreated($email, $name, $password, $emailVerifiedAt));

        return $this;
    }
}
