<?php

namespace Tests\Unit;

use App\Domain\Actions\LeaveQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class LeaveQuestActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $week;

    /** @var Quest */
    protected $quest;

    /** @var Campaign */
    protected $campaign;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var Skirmish */
    protected $skirmish;

    public function setUp(): void
    {
        parent::setUp();
        $this->quest = factory(Quest::class)->create();
        $this->squad = factory(Squad::class)->create([
            'province_id' => $this->quest->province_id
        ]);
        $this->week = factory(Week::class)->create();
        $this->week->adventuring_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        $this->campaign = factory(Campaign::class)->create([
            'week_id' => $this->week->id,
            'continent_id' => $this->quest->province->continent_id,
            'squad_id' => $this->squad->id
        ]);

        $this->campaignStop = factory(CampaignStop::class)->create([
            'campaign_id' => $this->campaign->id,
            'quest_id' => $this->quest->id
        ]);

        $this->skirmish = factory(Skirmish::class)->create([
            'quest_id' => $this->quest->id
        ]);
    }

    /**
     * @test
     */
    public function leaving_a_quest_when_the_week_is_locked_will_throw_an_exception()
    {
        $this->week->adventuring_locks_at = Date::now()->subHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        try {
            /** @var LeaveQuestAction $domainAction */
            $domainAction = app(LeaveQuestAction::class);
            $domainAction->execute($this->squad, $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals(CampaignException::CODE_WEEK_LOCKED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function leaving_a_quest_without_a_current_campaign_will_throw_an_exception()
    {
        $this->campaignStop->skirmishes()->sync([]);
        $this->campaignStop->delete();
        $this->campaign->delete();

        try {
            /** @var LeaveQuestAction $domainAction */
            $domainAction = app(LeaveQuestAction::class);
            $domainAction->execute($this->squad, $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals(CampaignException::CODE_NO_CURRENT_CAMPAIGN, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function leaving_a_quest_that_is_not_in_the_current_campaign_will_throw_an_exception()
    {
        $this->campaignStop->quest_id = factory(Quest::class)->create()->id;
        $this->campaignStop->save();

        try {
            /** @var LeaveQuestAction $domainAction */
            $domainAction = app(LeaveQuestAction::class);
            $domainAction->execute($this->squad, $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals(CampaignException::CODE_QUEST_NOT_IN_CAMPAIGN, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function leaving_a_quest_will_delete_the_associated_campaign_stop()
    {
        $stopUuid = $this->campaignStop->uuid;

        /** @var LeaveQuestAction $domainAction */
        $domainAction = app(LeaveQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        $campaignStop = CampaignStop::findUuid($stopUuid);
        $this->assertNull($campaignStop);
    }

    /**
     * @test
     */
    public function leaving_a_quest_will_remove_skirmishes_from_the_campaign_stop()
    {
        $stopUuid = $this->campaignStop->uuid;

        /** @var LeaveQuestAction $domainAction */
        $domainAction = app(LeaveQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        /*
         * We need to retrieve the trashed campaign stop to verify it's pivot relations are gone
         */
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::withTrashed()->where('uuid', '=', $stopUuid)->first();
        $this->assertEquals(0, $campaignStop->skirmishes()->count());

        $campaignStop = CampaignStop::findUuid($stopUuid);
        $this->assertNull($campaignStop);
    }

    /**
     * @test
     */
    public function you_can_leave_a_quest_and_skirmishes_when_at_a_different_province()
    {
        $this->squad->province_id = Province::query()
            ->where('id', '!=', $this->campaignStop->province_id)
            ->inRandomOrder()
            ->first()->id;

        $this->squad->save();
        $this->squad = $this->squad->fresh();

        $stopUuid = $this->campaignStop->uuid;

        /** @var LeaveQuestAction $domainAction */
        $domainAction = app(LeaveQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        /*
         * We need to retrieve the trashed campaign stop to verify it's pivot relations are gone
         */
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::withTrashed()->where('uuid', '=', $stopUuid)->first();
        $this->assertEquals(0, $campaignStop->skirmishes()->count());

        $campaignStop = CampaignStop::findUuid($stopUuid);
        $this->assertNull($campaignStop);
    }

    /**
     * @test
     */
    public function leaving_the_only_quest_of_a_campaign_will_delete_the_entire_campaign()
    {
        $campaignUuid = $this->campaign->uuid;

        /** @var LeaveQuestAction $domainAction */
        $domainAction = app(LeaveQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        $campaign = Campaign::findUuid($campaignUuid);
        $this->assertNull($campaign);
    }

    /**
     * @test
     */
    public function leaving_a_quest_of_a_campaign_with_multiple_quests_will_NOT_delete_the_campaign()
    {
        /*
         * Join a different quest, ie create another campaign stop
         */
        factory(CampaignStop::class)->create([
            'campaign_id' => $this->campaign->id
        ]);

        $campaignUuid = $this->campaign->uuid;

        /** @var LeaveQuestAction $domainAction */
        $domainAction = app(LeaveQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        $campaign = Campaign::findUuid($campaignUuid);
        $this->assertNotNull($campaign);
    }

}
