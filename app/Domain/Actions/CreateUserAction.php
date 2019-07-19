<?php


namespace App\Domain\Actions;


use App\Aggregates\UserAggregate;
use App\Domain\Models\User;
use Illuminate\Support\Str;

class CreateUserAction
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $emailVerifiedAt;

    public function __construct(string $email, string $name, string $password = null, string $emailVerifiedAt = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->$password = $password;
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    public function __invoke(): User
    {

        $uuid = Str::uuid();
        /** @var UserAggregate $userAggregate */
        $userAggregate = UserAggregate::retrieve($uuid);
        $userAggregate->createUser($this->email, $this->name, $this->password, $this->emailVerifiedAt)->persist();
        $user = User::uuid($uuid);
        return $user;
    }
}