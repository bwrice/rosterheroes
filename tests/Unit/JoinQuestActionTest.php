<?php

namespace Tests\Unit;

use App\Domain\Actions\JoinQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\Jobs\CreateSquadJoinsQuestProvinceEventJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JoinQuestActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $week;

    /** @var Quest */
    protected $quest;

    public function setUp(): void
    {
        parent::setUp();
        $this->quest = factory(Quest::class)->create();
        $this->squad = factory(Squad::class)->create([
            'province_id' => $this->quest->province_id
        ]);
        $this->week = factory(Week::class)->states('as-current', 'adventuring-open')->create();
    }

    /**
     * @test
     */
    public function enlist_action_will_throw_an_exception_if_the_week_is_locked()
    {
        $this->week->adventuring_locks_at = Date::now()->subHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        try {
            /** @var JoinQuestAction $domainAction */
            $domainAction = app(JoinQuestAction::class);
            $domainAction->execute($this->squad, $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals($exception->getCode(), CampaignException::CODE_WEEK_LOCKED);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function enlist_action_will_throw_an_exception_if_the_squad_is_not_at_the_quest_province()
    {
        $differentProvince = Province::query()->where('id', '!=', $this->quest->province_id)->inRandomOrder()->first();
        $this->squad->province_id = $differentProvince->id;
        $this->squad->save();

        try {
            /** @var JoinQuestAction $domainAction */
            $domainAction = app(JoinQuestAction::class);
            $domainAction->execute($this->squad->fresh(), $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals($exception->getCode(), CampaignException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_campaign_is_for_a_different_continent()
    {
        $continentID = $this->quest->province->continent_id;
        $differentContinent = Continent::query()->where('id', '!=', $continentID)->inRandomOrder()->first();
        factory(Campaign::class)->create([
            'continent_id' => $differentContinent->id,
            'squad_id' => $this->squad
        ]);

        try {
            /** @var JoinQuestAction $domainAction */
            $domainAction = app(JoinQuestAction::class);
            $domainAction->execute($this->squad->fresh(), $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals($exception->getCode(), CampaignException::CODE_DIFFERENT_CONTINENT);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_quests_per_week_limit_reached()
    {
        $continentID = $this->quest->province->continent_id;
        factory(Campaign::class)->create([
            'continent_id' => $continentID,
            'squad_id' => $this->squad
        ]);

        /** @var Squad $squad */
        $squad = \Mockery::mock($this->squad->fresh())->shouldReceive('getQuestsPerWeek')->andReturn(0)->getMock();

        try {
            /** @var JoinQuestAction $domainAction */
            $domainAction = app(JoinQuestAction::class);
            $domainAction->execute($squad, $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals($exception->getCode(), CampaignException::CODE_MAX_QUESTS_REACHED);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_a_campaign_stop_already_exists_for_that_quest()
    {
        $continentID = $this->quest->province->continent_id;
        $campaign = factory(Campaign::class)->create([
            'continent_id' => $continentID,
            'squad_id' => $this->squad
        ]);

        factory(CampaignStop::class)->create([
            'campaign_id' => $campaign->id,
            'quest_id' => $this->quest->id
        ]);

        try {
            /** @var JoinQuestAction $domainAction */
            $domainAction = app(JoinQuestAction::class);
            $domainAction->execute($this->squad->fresh(), $this->quest);
        } catch (CampaignException $exception) {
            $this->assertEquals($exception->getCode(), CampaignException::CODE_ALREADY_ENLISTED);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_a_campaign_if_none_exists()
    {
        $this->assertNull($this->squad->getCurrentCampaign());

        /** @var JoinQuestAction $domainAction */
        $domainAction = app(JoinQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        $currentCampaign = $this->squad->getCurrentCampaign();
        $this->assertNotNull($currentCampaign);
        $this->assertEquals($this->squad->id, $currentCampaign->squad_id);
        $this->assertEquals($this->week->id, $currentCampaign->week_id);

        $campaignStops = $currentCampaign->campaignStops;
        $this->assertEquals(1, $campaignStops->count());
        /** @var CampaignStop $campaignStop */
        $campaignStop = $campaignStops->first();
        $this->assertEquals($this->quest->id, $campaignStop->quest_id);
        $this->assertEquals($this->quest->province_id, $campaignStop->province_id);
        $this->assertEquals($currentCampaign->id, $campaignStop->campaign_id);
    }

    /**
     * @test
     */
    public function it_will_add_to_the_current_campaign_if_it_exists()
    {
        $campaignCreated = factory(Campaign::class)->create([
            'squad_id' => $this->squad->id,
            'week_id' => $this->week->id,
            'continent_id' => $this->quest->province->continent_id
        ]);

        /** @var JoinQuestAction $domainAction */
        $domainAction = app(JoinQuestAction::class);
        $this->squad = $this->squad->fresh();
        $domainAction->execute($this->squad, $this->quest);

        $currentCampaign = $this->squad->getCurrentCampaign();
        $this->assertEquals($campaignCreated->id, $currentCampaign->id);

        $campaignStops = $currentCampaign->campaignStops;
        $this->assertEquals(1, $campaignStops->count());
        /** @var CampaignStop $campaignStop */
        $campaignStop = $campaignStops->first();
        $this->assertEquals($this->quest->id, $campaignStop->quest_id);
        $this->assertEquals($this->quest->province_id, $campaignStop->province_id);
        $this->assertEquals($currentCampaign->id, $campaignStop->campaign_id);
    }

    /**
     * @test
     */
    public function it_will_dispatch_create_squad_joins_quest_event_job()
    {
        Queue::fake();

        /** @var JoinQuestAction $domainAction */
        $domainAction = app(JoinQuestAction::class);
        $domainAction->execute($this->squad, $this->quest);

        Queue::assertPushed(CreateSquadJoinsQuestProvinceEventJob::class, function (CreateSquadJoinsQuestProvinceEventJob $job) {
            return $job->squad->id === $this->squad->id && $job->quest->id === $this->quest->id && $job->week->id === $this->week->id;
        });
    }
}
