<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class UserCreated implements ShouldBeStored
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $emailVerifiedAt;

    public function __construct(string $email, string $name, string $emailVerifiedAt = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->emailVerifiedAt = $emailVerifiedAt;
    }
}
