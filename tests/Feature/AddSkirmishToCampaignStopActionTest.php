<?php

namespace Tests\Feature;

use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
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
            'province_id' => $this->quest->province_id
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
}
