<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\Exceptions\CampaignStopException;
use App\Factories\Models\SideQuestResultFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class AddSideQuestToCampaignStopActionTest extends TestCase
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
    }

    /**
     * @test
     */
    public function adding_a_side_quest_will_throw_an_exception_if_the_week_is_locked()
    {
        $this->week->adventuring_locks_at = Date::now()->subHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        try {

            /** @var JoinSideQuestAction $domainAction */
            $domainAction = app(JoinSideQuestAction::class);
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
    public function it_will_throw_an_exception_if_the_side_quest_does_not_belong_to_the_quest()
    {
        $quest = factory(Quest::class)->create();
        $this->sideQuest->quest_id = $quest->id;
        $this->sideQuest->save();
        $this->sideQuest = $this->sideQuest->fresh();

        try {

            /** @var JoinSideQuestAction $domainAction */
            $domainAction = app(JoinSideQuestAction::class);
            $domainAction->execute($this->campaignStop, $this->sideQuest);

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_QUEST_NON_MATCH, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_at_the_side_quest_province()
    {
        $diffProvince = Province::query()->where('id', '!=', $this->quest->id)->inRandomOrder()->first();
        $this->squad->province_id = $diffProvince->id;
        $this->squad->save();
        $this->squad = $this->squad->fresh();

        try {

            /** @var JoinSideQuestAction $domainAction */
            $domainAction = app(JoinSideQuestAction::class);
            $domainAction->execute($this->campaignStop, $this->sideQuest);

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_max_side_quest_count_has_already_been_reached()
    {
        try {

            $squadMock = \Mockery::mock($this->squad)
                ->shouldReceive('getSideQuestsPerQuest')
                ->andReturn(0)->getMock();

            // set the relation prop to the mock
            $this->campaignStop->campaign->squad = $squadMock;

            /** @var JoinSideQuestAction $domainAction */
            $domainAction = app(JoinSideQuestAction::class);
            $domainAction->execute($this->campaignStop, $this->sideQuest);

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_SIDE_QUEST_LIMIT_REACHED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_campaign_stop_already_has_the_side_quest()
    {
        try {

            // Make existing side quest result
            SideQuestResultFactory::new()->create([
                'side_quest_id' => $this->sideQuest->id,
                'campaign_stop_id' => $this->campaignStop->id
            ]);

            /** @var JoinSideQuestAction $domainAction */
            $domainAction = app(JoinSideQuestAction::class);
            $domainAction->execute($this->campaignStop->fresh(), $this->sideQuest->fresh());

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_SIDE_QUEST_ALREADY_ADDED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_add_a_side_quest_to_a_campaign_stop_with_no_side_quests()
    {
        $this->assertEquals(0, $this->campaignStop->sideQuests()->count());

        /** @var JoinSideQuestAction $domainAction */
        $domainAction = app(JoinSideQuestAction::class);
        $domainAction->execute($this->campaignStop, $this->sideQuest);

        $sideQuests = $this->campaignStop->fresh()->sideQuests;
        $this->assertEquals(1, $sideQuests->count());
    }

}
