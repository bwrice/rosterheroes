<?php

namespace Tests\Unit;

use App\Domain\Actions\LeaveSkirmishAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignStopException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class LeaveSkirmishActionTest extends TestCase
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

    /** @var Skirmish */
    protected $skirmish;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->week->everything_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);
        $this->squad = factory(Squad::class)->create();
        $this->quest = factory(Quest::class)->create([
            'province_id' => $this->squad->province_id
        ]);
        $this->skirmish = factory(Skirmish::class)->create([
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

        $this->campaignStop->skirmishes()->attach($this->skirmish->id);
    }

    /**
     * @test
     */
    public function leaving_a_skirmish_will_throw_an_exception_if_the_week_is_locked()
    {
        $this->week->everything_locks_at = Date::now()->subHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        try {
            /** @var LeaveSkirmishAction $domainAction */
            $domainAction = app(LeaveSkirmishAction::class);
            $domainAction->execute($this->campaignStop, $this->skirmish);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_WEEK_LOCKED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function the_skirmish_must_belong_to_the_same_quest_as_the_campaign_stop()
    {
        $quest = factory(Quest::class)->create();
        $this->campaignStop->quest_id = $quest->id;
        $this->campaignStop->save();

        try {
            /** @var LeaveSkirmishAction $domainAction */
            $domainAction = app(LeaveSkirmishAction::class);
            $domainAction->execute($this->campaignStop->fresh(), $this->skirmish);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_QUEST_NON_MATCH, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_skirmish_does_not_belong_to_the_campaign_stop()
    {
        $this->campaignStop->skirmishes()->sync([]);

        try {
            /** @var LeaveSkirmishAction $domainAction */
            $domainAction = app(LeaveSkirmishAction::class);
            $domainAction->execute($this->campaignStop->fresh(), $this->skirmish);
        } catch (CampaignStopException $exception) {
            $this->assertEquals(CampaignStopException::CODE_SKIRMISH_NOT_ADDED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }


}
