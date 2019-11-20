<?php

namespace App\Projectors;

use App\Domain\Models\User;
use App\StorableEvents\UserCreated;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class UserProjector implements Projector
{
    use ProjectsEvents;

    public function onUserCreate(UserCreated $event, string $aggregateUuid)
    {
        User::query()->create([
            'uuid' => $aggregateUuid,
            'email' => $event->email,
            'password' => $event->hashedPassword,
            'name' => $event->name,
            'email_verified_at' => $event->emailVerifiedAt
        ]);
    }
}
