<?php

namespace Tests\Feature;

use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Actions\SetupQuestForNextWeek;
use App\Domain\Models\Province;
use App\Domain\Models\TravelType;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Models\QuestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetupQuestForNextWeekTest extends TestCase
{
    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_week_is_not_finalizing()
    {
        $week = factory(Week::class)->states('as-current', 'adventuring-closed')->create();
        $this->assertEquals(CurrentWeek::id(), $week->id);

        $quest = QuestFactory::new()->withTravelType(TravelType::REALM)->create();

        $previousProvinceID = $quest->province_id;

        /** @var SetupQuestForNextWeek $domainAction */
        $domainAction = app(SetupQuestForNextWeek::class);

        try {
            $domainAction->execute($quest);
        } catch (\Exception $exception) {
            $this->assertEquals($previousProvinceID, $quest->fresh()->province_id);
            return;
        }
        $this->fail('Exception not thrown');
    }

    /**
     * @test
     */
    public function it_will_not_move_stationary_quests()
    {
        $quest = QuestFactory::new()->withTravelType(TravelType::STATIONARY)->create();

        $previousProvinceID = $quest->province_id;

        /** @var SetupQuestForNextWeek $domainAction */
        $domainAction = app(SetupQuestForNextWeek::class);
        $domainAction->execute($quest);

        $this->assertEquals($previousProvinceID, $quest->fresh()->province_id);
    }

    /**
     * @test
     */
    public function it_will_move_a_border_travel_quest_to_a_bordered_province()
    {
        $quest = QuestFactory::new()->withTravelType(TravelType::BORDER)->create();

        $borderIDs = $quest->province->borders()->pluck('id')->values()->toArray();

        /** @var SetupQuestForNextWeek $domainAction */
        $domainAction = app(SetupQuestForNextWeek::class);
        $domainAction->execute($quest);

        $this->assertTrue(in_array($quest->fresh()->province_id, $borderIDs));
    }

    /**
     * @test
     */
    public function it_will_move_a_territory_travel_quest_to_a_province_in_the_same_territory()
    {
        $quest = QuestFactory::new()->withTravelType(TravelType::TERRITORY)->create();
        $previousProvince = $quest->province;

        /** @var SetupQuestForNextWeek $domainAction */
        $domainAction = app(SetupQuestForNextWeek::class);
        $domainAction->execute($quest);

        $currentProvince = $quest->fresh()->province;
        $this->assertNotEquals($previousProvince->id, $currentProvince->id);
        $this->assertEquals($previousProvince->territory_id, $currentProvince->territory_id);
    }

    /**
     * @test
     */
    public function it_will_move_a_continent_travel_quest_to_a_province_on_the_same_continent()
    {
        $quest = QuestFactory::new()->withTravelType(TravelType::CONTINENT)->create();
        $previousProvince = $quest->province;

        /** @var SetupQuestForNextWeek $domainAction */
        $domainAction = app(SetupQuestForNextWeek::class);
        $domainAction->execute($quest);

        $currentProvince = $quest->fresh()->province;
        $this->assertNotEquals($previousProvince->id, $currentProvince->id);
        $this->assertEquals($previousProvince->continent_id, $currentProvince->continent_id);
    }

    /**
     * @test
     */
    public function it_will_move_a_realm_travel_quest_to_any_other_province()
    {
        $quest = QuestFactory::new()->withTravelType(TravelType::REALM)->create();
        $previousProvince = $quest->province;

        /** @var SetupQuestForNextWeek $domainAction */
        $domainAction = app(SetupQuestForNextWeek::class);
        $domainAction->execute($quest);

        $currentProvince = $quest->fresh()->province;
        $this->assertNotEquals($previousProvince->id, $currentProvince->id);
    }
}
