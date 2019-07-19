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
    private $hashedPassword;
    /**
     * @var string
     */
    public $emailVerifiedAt;

    public function __construct(string $email, string $name, string $hashedPassword = null, string $emailVerifiedAt = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->hashedPassword = $hashedPassword;
        $this->emailVerifiedAt = $emailVerifiedAt;
    }
}
