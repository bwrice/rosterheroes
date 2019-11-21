<?php

namespace Tests\Feature;

use App\Domain\Actions\AddSkirmishToCampaignStopAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\Exceptions\CampaignStopException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class AddSkirmishToCampaignStopActionTest extends TestCase
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
    }

    /**
     * @test
     */
    public function adding_a_skirmish_will_throw_an_exception_if_the_week_is_locked()
    {
        $this->week->everything_locks_at = Date::now()->subHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        try {

            /** @var AddSkirmishToCampaignStopAction $domainAction */
            $domainAction = app(AddSkirmishToCampaignStopAction::class);
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
    public function it_will_throw_an_exception_if_the_skirmish_does_not_belong_to_the_quest()
    {
        $quest = factory(Quest::class)->create();
        $this->skirmish->quest_id = $quest->id;
        $this->skirmish->save();
        $this->skirmish = $this->skirmish->fresh();

        try {

            /** @var AddSkirmishToCampaignStopAction $domainAction */
            $domainAction = app(AddSkirmishToCampaignStopAction::class);
            $domainAction->execute($this->campaignStop, $this->skirmish);

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_INVALID_SKIRMISH, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_at_the_skirmish_province()
    {
        $diffProvince = Province::query()->where('id', '!=', $this->quest->id)->inRandomOrder()->first();
        $this->squad->province_id = $diffProvince->id;
        $this->squad->save();
        $this->squad = $this->squad->fresh();

        try {

            /** @var AddSkirmishToCampaignStopAction $domainAction */
            $domainAction = app(AddSkirmishToCampaignStopAction::class);
            $domainAction->execute($this->campaignStop, $this->skirmish);

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_max_skirmish_count_has_already_been_reached()
    {
        try {

            $squadMock = \Mockery::mock($this->squad)
                ->shouldReceive('getSkirmishesPerQuest')
                ->andReturn(0)->getMock();

            // set the relation prop to the mock
            $this->campaignStop->campaign->squad = $squadMock;

            /** @var AddSkirmishToCampaignStopAction $domainAction */
            $domainAction = app(AddSkirmishToCampaignStopAction::class);
            $domainAction->execute($this->campaignStop, $this->skirmish);

        } catch (CampaignStopException $exception) {

            $this->assertEquals(CampaignStopException::CODE_SKIRMISH_LIMIT_REACHED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }
}
