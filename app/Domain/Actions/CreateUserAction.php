<?php


namespace App\Domain\Actions;

use App\Domain\Models\EmailSubscription;
use App\Domain\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserAction
{
    public function execute(string $email, string $name, string $password = null, string $emailVerifiedAt = null): User
    {
        /** @var User $user */
        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => $email,
            'password' => Hash::make($password),
            'name' => $name,
            'email_verified_at' => $emailVerifiedAt
        ]);

        $user->emailSubscriptions()->saveMany(EmailSubscription::all());

        return $user->fresh();
    }
}
