<?php

namespace Tests\Feature;

use App\Domain\Actions\DispatchPendingTreasureEmails;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\EmailSubscription;
use App\Domain\Models\Squad;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\SquadFactory;
use App\Mail\TreasuresPending;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DispatchPendingTreasureEmailsTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = SquadFactory::new()->create();
        $squadNotificationSub = EmailSubscription::squadNotifications();
        $this->squad->user->emailSubscriptions()->save($squadNotificationSub);
    }

    /**
     * @param int $weeksBack
     * @return DispatchPendingTreasureEmails
     */
    protected function getDomainAction(int $weeksBack = 1)
    {
        /** @var DispatchPendingTreasureEmails $domainAction */
        $domainAction = app(DispatchPendingTreasureEmails::class);
        return $domainAction->setWeeksBack($weeksBack);
    }

    /**
     * @test
     */
    public function it_will_not_queue_emails_to_squads_with_a_recent_opened_chest()
    {
        Mail::fake();
        $chest = ChestFactory::new()->opened()->withSquadID($this->squad->id)->create();

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(TreasuresPending::class, function (TreasuresPending $mail) {
            return $mail->squad->id === $this->squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_queue_emails_for_squads_with_only_the_newcomer_chest_unopened()
    {
        Mail::fake();
        $newcomerBlueprint = ChestBlueprint::query()
            ->where('description', '=', 'Newcomer Chest')
            ->first();

        $chest = ChestFactory::new()->withChestBlueprintID($newcomerBlueprint->id)->withSquadID($this->squad->id)->create();

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(TreasuresPending::class, function (TreasuresPending $mail) {
            return $mail->squad->id === $this->squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_queue_emails_to_squads_with_a_recent_unopened_chest()
    {
        Mail::fake();
        $chestsCount = rand(2, 5);
        $chestFactory = ChestFactory::new()->withSquadID($this->squad->id);
        for ($i = 1; $i <= $chestsCount; $i++) {
            $chestFactory->create();
        }

        $this->getDomainAction()->execute();

        Mail::assertQueued(TreasuresPending::class, function (TreasuresPending $mail) use ($chestsCount) {
            return $mail->squad->id === $this->squad->id && $mail->unopenedChestsCount === $chestsCount;
        });
    }

    /**
     * @test
     */
    public function it_will_not_queue_emails_for_users_not_subscribed_to_squad_notifications()
    {
        Mail::fake();
        $squad = SquadFactory::new()->create();
        $chestsCount = rand(2, 5);
        $chestFactory = ChestFactory::new()->withSquadID($squad->id);
        for ($i = 1; $i <= $chestsCount; $i++) {
            $chestFactory->create();
        }

        $user = $squad->user;

        // Subscribe to everything
        $emailSubs = EmailSubscription::all();
        $user->emailSubscriptions()->saveMany($emailSubs);

        // Unsubscribe to squad notifications
        $squadNotificationsEmailSub = EmailSubscription::squadNotifications();
        $user->emailSubscriptions()->detach([$squadNotificationsEmailSub->id]);

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(TreasuresPending::class, function (TreasuresPending $mail) use ($squad) {
            return $mail->squad->id === $squad->id;
        });
    }
}
