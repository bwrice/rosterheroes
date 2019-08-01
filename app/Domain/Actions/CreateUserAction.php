<?php


namespace App\Domain\Actions;


use App\Aggregates\UserAggregate;
use App\Domain\Models\User;
use Illuminate\Support\Str;

class CreateUserAction
{
    public function execute(string $email, string $name, string $password = null, string $emailVerifiedAt = null): User
    {
        $uuid = Str::uuid();
        /** @var UserAggregate $userAggregate */
        $userAggregate = UserAggregate::retrieve($uuid);
        $userAggregate->createUser($email, $name, $password, $emailVerifiedAt)->persist();
        $user = User::uuid($uuid);
        return $user;
    }
}