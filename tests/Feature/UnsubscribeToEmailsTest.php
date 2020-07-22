<?php

namespace Tests\Feature;

use App\Domain\Models\EmailSubscription;
use App\Domain\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UnsubscribeToEmailsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_fail_authorization_if_the_unsubscribe_email_url_is_not_signed()
    {
        $user = factory(User::class)->create();
        $emailSub = EmailSubscription::query()->first();
        $response = $this->get(route('emails.unsubscribe', [
            'user' => $user->uuid,
            'emailSubscription' => $emailSub->id
        ]));
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_unsubscribe_a_user_from_emails()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $emailSub = EmailSubscription::query()->first();

        $user->emailSubscriptions()->save($emailSub);
        $this->assertEquals(1, $user->emailSubscriptions()->count());

        $signedURL = URL::signedRoute('emails.unsubscribe', [
            'user' => $user->uuid,
            'emailSubscription' => $emailSub->id
        ]);

        $response = $this->get($signedURL);
        $response->assertStatus(200);

        $this->assertEquals(0, $user->fresh()->emailSubscriptions()->count());
    }

    /**
     * @test
     */
    public function it_will_unsubscribe_a_user_from_all_emails()
    {
        $this->assertTrue(true);
        /** @var User $user */
        $user = factory(User::class)->create();
        $emailSubs = EmailSubscription::all();

        $user->emailSubscriptions()->saveMany($emailSubs);
        $this->assertGreaterThan(1, $user->emailSubscriptions()->count());

        $signedURL = URL::signedRoute('emails.unsubscribe', [
            'user' => $user->uuid,
            'emailSubscription' => 'all'
        ]);

        $response = $this->get($signedURL);
        $response->assertStatus(200);

        $this->assertEquals(0, $user->fresh()->emailSubscriptions()->count());
    }
}
