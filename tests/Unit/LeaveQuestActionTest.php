<?php

namespace Tests\Unit;

use App\Domain\Actions\LeaveQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\Quest;
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

    public function setUp(): void
    {
        parent::setUp();
        $this->quest = factory(Quest::class)->create();
        $this->squad = factory(Squad::class)->create([
            'province_id' => $this->quest->province_id
        ]);
        $this->week = factory(Week::class)->create();
        $this->week->everything_locks_at = Date::now()->addHour();
        $this->week->save();
        Week::setTestCurrent($this->week);

        $this->campaign = factory(Campaign::class)->create([
            'week_id' => $this->week->id,
            'continent_id' => $this->quest->province->continent_id,
            'squad_id' => $this->squad->id
        ]);
    }

    /**
     * @test
     */
    public function cannot_leave_quest_when_the_week_is_locked()
    {
        $this->week->everything_locks_at = Date::now()->subHour();
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
}
