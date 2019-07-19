<?php

namespace App\Aggregates;

use App\StorableEvents\UserCreated;
use Spatie\EventProjector\AggregateRoot;

final class UserAggregate extends AggregateRoot
{
    public function createUser(string $email, string $name, string $emailVerifiedAt = null)
    {
        $this->recordThat(new UserCreated($email, $name, $emailVerifiedAt));

        return $this;
    }
}
