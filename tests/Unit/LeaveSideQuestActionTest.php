<?php

namespace Tests\Unit;

use App\Domain\Actions\LeaveSideQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignStopException;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class LeaveSideQuestActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $week;

    /** @var Campaign */
    protected $campaign;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var Quest */
    protected $quest;

    /** @var SideQuest */
    protected $sideQuest;

    /** @var SideQuestResult */
    protected $sideQuestResult;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->week->adventuring_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);
        $this->squad = factory(Squad::class)->create();
        $this->quest = factory(Quest::class)->create([
            'province_id' => $this->squad->province_id
        ]);
        $this->sideQuest = factory(SideQuest::class)->create([
            'quest_id' => $this->quest->id
        ]);
        $this->campaign = factory(Campaign::class)->create([
            'continent_id' => $this->quest->province->continent_id,
            'squad_id' => $this->squad->id,
            'week_id' => $this->week->id
        ]);

        $this->campaignStop = factory(CampaignStop::class)->create([
            'quest_id' => $this->quest->id,
            'province_id' => $this->quest->province_id,
            'campaign_id' => $this->campaign->id
        ]);

        $this->sideQuestResult = SideQuestResultFactory::new()->create([
            'campaign_stop_id' => $this->campaignStop->id,
            'side_quest_id' => $this->sideQuest->id
        ]);
    }

    /**
     * @test
     */
    public function leaving_a_side_quest_will_throw_an_exception_if_the_week_is_locked()
    {
        $this->week->adventuring_locks_at = Date::now()->subHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        try {
            /** @var LeaveSideQuestAction $domainAction */
            $domainAction = app(LeaveSideQuestAction::class);
            $domainAction->execute($this->campaignStop, $this->sideQuest);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_WEEK_LOCKED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function leaving_a_side_quest_for_a_campaign_of_a_different_week_will_throw_an_exception()
    {
        $week = factory(Week::class)->create();
        $week->adventuring_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);

        try {
            /** @var LeaveSideQuestAction $domainAction */
            $domainAction = app(LeaveSideQuestAction::class);
            $domainAction->execute($this->campaignStop, $this->sideQuest);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_CAMPAIGN_FOR_PREVIOUS_WEEK, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function the_side_quest_must_belong_to_the_same_quest_as_the_campaign_stop()
    {
        $quest = factory(Quest::class)->create();
        $this->campaignStop->quest_id = $quest->id;
        $this->campaignStop->save();

        try {
            /** @var LeaveSideQuestAction $domainAction */
            $domainAction = app(LeaveSideQuestAction::class);
            $domainAction->execute($this->campaignStop->fresh(), $this->sideQuest);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_QUEST_NON_MATCH, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_side_quest_does_not_belong_to_the_campaign_stop()
    {
        $this->sideQuestResult->delete();

        try {
            /** @var LeaveSideQuestAction $domainAction */
            $domainAction = app(LeaveSideQuestAction::class);
            $domainAction->execute($this->campaignStop->fresh(), $this->sideQuest);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_SIDE_QUEST_NOT_ADDED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_leave_a_side_quest()
    {
        $this->assertEquals(1, $this->campaignStop->sideQuests()->count());

        /** @var LeaveSideQuestAction $domainAction */
        $domainAction = app(LeaveSideQuestAction::class);
        $domainAction->execute($this->campaignStop->fresh(), $this->sideQuest);

        $this->assertEquals(0, $this->campaignStop->fresh()->sideQuests()->count());
    }


}
