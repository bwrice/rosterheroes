<?php

namespace Tests\Feature;

use App\Domain\Actions\Emails\DispatchAlmostReadyForWeekEmails;
use App\Domain\Actions\Emails\DispatchPendingTreasureEmails;
use App\Domain\Models\EmailSubscription;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SquadFactory;
use App\Mail\AlmostReadyForWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DispatchAlmostReadyForWeekEmailsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Squad
     */
    protected $squad;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = SquadFactory::new()->create();
        $this->squad->user->emailSubscriptions()->save(EmailSubscription::squadNotifications());
        $this->currentWeek = factory(Week::class)->states('as-current')->create();
    }

    /**
     * @return DispatchAlmostReadyForWeekEmails
     */
    protected function getDomainAction()
    {
        return app(DispatchAlmostReadyForWeekEmails::class);
    }
    /**
     * @test
     */
    public function it_will_send_an_email_to_a_squad_with_no_campaign_for_the_current_week()
    {
        $this->assertNull($this->squad->getCurrentCampaign());

        Mail::fake();

        $this->getDomainAction()->execute();

        Mail::assertQueued(AlmostReadyForWeek::class, function (AlmostReadyForWeek $almostReadyForWeek) {
            return $this->squad->id === $almostReadyForWeek->squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_send_an_email_to_a_user_unsubscribed_to_squad_notifications()
    {
        // Subscribe user to everything EXCEPT squad notifications
        $subs = EmailSubscription::all()->reject(function (EmailSubscription $emailSubscription) {
            return $emailSubscription->name === EmailSubscription::SQUAD_NOTIFICATIONS;
        });
        $this->squad->user->emailSubscriptions()->sync($subs->pluck('id')->toArray());

        $this->assertNull($this->squad->getCurrentCampaign());

        Mail::fake();

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(AlmostReadyForWeek::class, function (AlmostReadyForWeek $almostReadyForWeek) {
            return $this->squad->id === $almostReadyForWeek->squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_email_a_squad_with_full_campaign_but_with_heroes_missing_spirits()
    {
        $campaign = CampaignFactory::new()->withSquadID($this->squad->id)->withWeekID($this->currentWeek->id)->create();

        for ($i = 1; $i <= $this->squad->getQuestsPerWeek(); $i++) {
            $campaignStop = CampaignStopFactory::new()->withCampaignID($campaign->id)->create();
            for ($i = 1; $i <= $this->squad->getSideQuestsPerQuest(); $i++) {
                SideQuestResultFactory::new()->withCampaignStopID($campaignStop->id)->create();
            }
        }

        // Create a hero with a player spirit
        $playerSpirit = PlayerSpiritFactory::new()->forWeek($this->currentWeek)->create();
        $hero = HeroFactory::new()->forSquad($this->squad)->create();
        $hero->player_spirit_id = $playerSpirit->id;
        $hero->save();

        // Create a hero with NO spirit
        HeroFactory::new()->forSquad($this->squad)->create();

        Mail::fake();

        $this->getDomainAction()->execute();

        Mail::assertQueued(AlmostReadyForWeek::class, function (AlmostReadyForWeek $almostReadyForWeek) {
            return $this->squad->id === $almostReadyForWeek->squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_email_a_squad_with_a_full_campaign_and_no_empty_heroes()
    {
        $campaign = CampaignFactory::new()->withSquadID($this->squad->id)->withWeekID($this->currentWeek->id)->create();

        for ($i = 1; $i <= $this->squad->getQuestsPerWeek(); $i++) {
            $campaignStop = CampaignStopFactory::new()->withCampaignID($campaign->id)->create();
            for ($i = 1; $i <= $this->squad->getSideQuestsPerQuest(); $i++) {
                SideQuestResultFactory::new()->withCampaignStopID($campaignStop->id)->create();
            }
        }

        // Create a hero with a player spirit
        $playerSpirit = PlayerSpiritFactory::new()->forWeek($this->currentWeek)->create();
        $hero = HeroFactory::new()->forSquad($this->squad)->create();
        $hero->player_spirit_id = $playerSpirit->id;
        $hero->save();

        Mail::fake();

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(AlmostReadyForWeek::class, function (AlmostReadyForWeek $almostReadyForWeek) {
            return $this->squad->id === $almostReadyForWeek->squad->id;
        });
    }
}
