<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateUserAction;
use App\Domain\Models\EmailSubscription;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * @return CreateUserAction
     */
    protected function getDomainAction()
    {
        return app(CreateUserAction::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_new_user()
    {
        $password = Str::random(10);
        $email = $this->faker->email;
        $name = $this->faker->name;
        $user = $this->getDomainAction()->execute($email, $name, $password);
        $this->assertNotNull($user);

        $this->assertEquals($email, $user->email);
        $this->assertEquals($name, $user->name);
        $this->assertTrue(Hash::check($password, $user->getAuthPassword()));
    }

    /**
     * @test
     */
    public function it_will_subscribe_a_user_to_all_email_subs()
    {
        $user = $this->getDomainAction()->execute($this->faker->email, $this->faker->name, Str::random(10));

        $emailSubs = EmailSubscription::all();

        $this->assertEquals($user->emailSubscriptions->pluck('id')->values()->toArray(), $emailSubs->pluck('id')->values()->toArray());
    }
}
