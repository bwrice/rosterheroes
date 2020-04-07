<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Actions\Combat\BuildSideQuestGroup;
use App\Domain\Actions\Combat\ProcessSideQuestResult;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestResultTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var SideQuest */
    protected $sideQuest;

    public function setUp(): void
    {
        parent::setUp();
        $this->campaignStop = CampaignStopFactory::new()->create();
        $this->sideQuest = SideQuestFactory::new()->create();
        $this->campaignStop->sideQuests()->save($this->sideQuest);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_campaign_stop_has_not_joined_the_side_quest()
    {
        $this->campaignStop->sideQuests()->sync([]);

        try {
            /** @var ProcessSideQuestResult $domainAction */
            $domainAction = app(ProcessSideQuestResult::class);
            $domainAction->execute($this->campaignStop, $this->sideQuest);
        } catch (\Exception $exception) {
            $sideQuestResult = SideQuestResult::query()
                ->where('side_quest_id', '=', $this->sideQuest->id)
                ->where('campaign_stop_id', '=', $this->campaignStop->id)->first();
            $this->assertNull($sideQuestResult);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_side_result_with_a_battlefield_set_event()
    {
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $domainAction->setMaxMoments(5);
        $sideQuestResult = $domainAction->execute($this->campaignStop, $this->sideQuest);
        $this->assertEquals($this->campaignStop->id, $sideQuestResult->campaign_stop_id);
        $this->assertEquals($this->sideQuest->id, $sideQuestResult->side_quest_id);
        $this->assertNull($sideQuestResult->rewards_processed_at);
        $sideQuestEvents = $sideQuestResult->sideQuestEvents()
            ->where('event_type', '=', SideQuestEvent::TYPE_BATTLEGROUND_SET)->get();
        $this->assertEquals(1, $sideQuestEvents->count());
    }

    /**
     * @test
     */
    public function it_will_create_a_side_quest_defeat_event_if_the_squad_is_defeated()
    {
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        $combatSquad = CombatSquadFactory::new()->create();
        $combatSquadMock = \Mockery::mock($combatSquad, [
            'isDefeated' => true
        ])->makePartial();

        $buildCombatSquadMock = \Mockery::mock(BuildCombatSquad::class)
            ->shouldReceive('execute')
            ->andReturn($combatSquadMock)->getMock();

        app()->instance(BuildCombatSquad::class, $buildCombatSquadMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->campaignStop, $this->sideQuest);

        $defeatEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT)->first();
        $this->assertNotNull($defeatEvent);
    }

    /**
     * @test
     */
    public function it_will_create_a_side_quest_victory_event_if_the_side_quest_group_is_defeated()
    {
        // mock RunCombatTurn
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        // mock CombatSquad
        $combatSquad = CombatSquadFactory::new()->create();
        $combatSquadMock = \Mockery::mock($combatSquad, [
            'isDefeated' => false
        ])->makePartial();

        $buildCombatSquadMock = \Mockery::mock(BuildCombatSquad::class)
            ->shouldReceive('execute')
            ->andReturn($combatSquadMock)->getMock();

        app()->instance(BuildCombatSquad::class, $buildCombatSquadMock);

        // mock SideQuestGroup
        $sideQuestGroup = SideQuestGroupFactory::new()->create();
        $sideQuestGroupMock = \Mockery::mock($sideQuestGroup, [
            'isDefeated' => true
        ])->makePartial();

        $buildSideQuestGroupMock = \Mockery::mock(BuildSideQuestGroup::class)
            ->shouldReceive('execute')
            ->andReturn($sideQuestGroupMock)->getMock();

        app()->instance(BuildSideQuestGroup::class, $buildSideQuestGroupMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->campaignStop, $this->sideQuest);

        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent);
    }

    /**
     * @test
     */
    public function it_will_create_a_side_quest_draw_event_if_the_max_moments_reached()
    {
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        // mock CombatSquad
        $combatSquad = CombatSquadFactory::new()->create();
        $combatSquadMock = \Mockery::mock($combatSquad, [
            'isDefeated' => false
        ])->makePartial();

        $buildCombatSquadMock = \Mockery::mock(BuildCombatSquad::class)
            ->shouldReceive('execute')
            ->andReturn($combatSquadMock)->getMock();

        app()->instance(BuildCombatSquad::class, $buildCombatSquadMock);

        // mock SideQuestGroup
        $sideQuestGroup = SideQuestGroupFactory::new()->create();
        $sideQuestGroupMock = \Mockery::mock($sideQuestGroup, [
            'isDefeated' => false
        ])->makePartial();

        $buildSideQuestGroupMock = \Mockery::mock(BuildSideQuestGroup::class)
            ->shouldReceive('execute')
            ->andReturn($sideQuestGroupMock)->getMock();

        app()->instance(BuildSideQuestGroup::class, $buildSideQuestGroupMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $maxMoments = rand(2, 10);
        $domainAction->setMaxMoments($maxMoments);

        $sideQuestResult = $domainAction->execute($this->campaignStop, $this->sideQuest);

        /** @var SideQuestEvent $drawEvent */
        $drawEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DRAW)->first();
        $this->assertNotNull($drawEvent);
        $this->assertEquals($maxMoments, $drawEvent->moment);
    }

    /**
     * @test
     */
    public function it_will_not_draw_if_the_one_side_is_defeated_on_final_moment()
    {
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        // mock CombatSquad
        $combatSquad = CombatSquadFactory::new()->create();
        $combatSquadMock = \Mockery::mock($combatSquad, [
            'isDefeated' => false
        ])->makePartial();

        $buildCombatSquadMock = \Mockery::mock(BuildCombatSquad::class)
            ->shouldReceive('execute')
            ->andReturn($combatSquadMock)->getMock();

        app()->instance(BuildCombatSquad::class, $buildCombatSquadMock);

        // mock SideQuestGroup
        $sideQuestGroup = SideQuestGroupFactory::new()->create();
        $sideQuestGroupMock = \Mockery::mock($sideQuestGroup, [
            'isDefeated' => true
        ])->makePartial();

        $buildSideQuestGroupMock = \Mockery::mock(BuildSideQuestGroup::class)
            ->shouldReceive('execute')
            ->andReturn($sideQuestGroupMock)->getMock();

        app()->instance(BuildSideQuestGroup::class, $buildSideQuestGroupMock);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        //Set max moments to 1 so it ends on first moment
        $domainAction->setMaxMoments(1);
        $sideQuestResult = $domainAction->execute($this->campaignStop, $this->sideQuest);

        /** @var SideQuestEvent $victoryEvent */
        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent);
        $this->assertEquals(1, $victoryEvent->moment);

        $drawEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DRAW)->first();
        $this->assertNull($drawEvent);
    }

    /**
     * @test
     * @param $referenceID
     * @throws \Exception
     * @dataProvider provides_a_beginner_squad_will_be_victorious_against_easy_side_quests
     */
    public function a_beginner_squad_will_be_victorious_against_easy_side_quests($referenceID)
    {
        $heroFactory = HeroFactory::new();
        $squad = SquadFactory::new()->withHeroes(collect([
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerRanger()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerSorcerer()->withCompletedGamePlayerSpirit(),
        ]))->create();

        $campaignStop = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withSquadID($squad->id))->create();

        /** @var SideQuest $sideQuest */
        $sideQuest = SideQuest::query()->whereHas('sideQuestBlueprint', function (Builder $builder) use ($referenceID) {
            return $builder->where('reference_id', '=', $referenceID);
        })->first();
        $campaignStop->sideQuests()->save($sideQuest);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($campaignStop, $sideQuest);
        $this->assertNotNull($sideQuestResult);
        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent);
        $eventCount = $sideQuestResult->sideQuestEvents()->count();
        $this->assertGreaterThan(15, $eventCount);
        $this->assertLessThan(250, $eventCount);
    }

    public function provides_a_beginner_squad_will_be_victorious_against_easy_side_quests()
    {
        return [
            [
                'referenceID' => 'A',
            ],
            [
                'referenceID' => 'B',
            ],
            [
                'referenceID' => 'C',
            ],
            [
                'referenceID' => 'O',
            ],
            [
                'referenceID' => 'P',
            ],
        ];
    }

    /**
     * @test
     */
    public function a_beginner_squad_will_be_defeated_by_a_large_skeleton_pack()
    {
        $heroFactory = HeroFactory::new();
        $squad = SquadFactory::new()->withHeroes(collect([
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerRanger()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerSorcerer()->withCompletedGamePlayerSpirit(),
        ]))->create();

        $campaignStop = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withSquadID($squad->id))->create();

        /** @var SideQuest $sideQuest */
        $sideQuest = SideQuest::query()->where('name', '=', 'Large Skeleton Group')->first();
        $campaignStop->sideQuests()->save($sideQuest);

        /** @var ProcessSideQuestResult $domainAction */
        $domainAction = app(ProcessSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($campaignStop, $sideQuest);
        $this->assertNotNull($sideQuestResult);
        $defeatEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT)->first();
        $this->assertNotNull($defeatEvent);
        $eventCount = $sideQuestResult->sideQuestEvents()->count();
        $this->assertGreaterThan(25, $eventCount);
        $this->assertLessThan(250, $eventCount);
    }

}
