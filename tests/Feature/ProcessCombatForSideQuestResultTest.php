<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Actions\Combat\BuildSideQuestGroup;
use App\Domain\Actions\Combat\ProcessCombatForSideQuestResult;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SquadFactory;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class ProcessCombatForSideQuestResultTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    /** @var SideQuestResult */
    protected $sideQuestResult;

    public function setUp(): void
    {
        parent::setUp();
        $week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $campaignFactory = CampaignFactory::new()->withWeekID($week->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $this->sideQuestResult = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory)->create();
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_side_quest_combat_has_already_been_processed()
    {
        $processedAt = Date::now()->subSeconds(rand(50, 100));
        $this->sideQuestResult->combat_processed_at = $processedAt;
        $this->sideQuestResult->save();

        try {
            /** @var ProcessCombatForSideQuestResult $domainAction */
            $domainAction = app(ProcessCombatForSideQuestResult::class);
            $domainAction->execute($this->sideQuestResult->fresh());
        } catch (\Exception $exception) {
            $this->assertEquals($processedAt->timestamp, $this->sideQuestResult->fresh()->combat_processed_at->timestamp);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_combat_processed_at()
    {
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $domainAction->setMaxMoments(5);
        $this->assertNull($this->sideQuestResult->combat_processed_at);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult);
        $this->assertNotNull($sideQuestResult->fresh()->combat_processed_at);
    }

    /**
     * @test
     */
    public function it_will_create_side_result_with_a_battlefield_set_event()
    {
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $domainAction->setMaxMoments(5);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult);
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

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult);

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

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult);

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

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $maxMoments = rand(2, 10);
        $domainAction->setMaxMoments($maxMoments);

        $sideQuestResult = $domainAction->execute($this->sideQuestResult);

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

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        //Set max moments to 1 so it ends on first moment
        $domainAction->setMaxMoments(1);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult);

        /** @var SideQuestEvent $victoryEvent */
        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent);
        $this->assertEquals(1, $victoryEvent->moment);

        $drawEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DRAW)->first();
        $this->assertNull($drawEvent);
    }

    /**
     * @test
     * @param $minionsArrays
     * @throws \Throwable
     * @dataProvider provides_a_beginner_squad_will_be_victorious_against_easy_side_quests
     */
    public function a_beginner_squad_will_be_victorious_against_easy_side_quests($minionsArrays)
    {
        $heroFactory = HeroFactory::new();
        $squad = SquadFactory::new()->withHeroes(collect([
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerRanger()->withCompletedGamePlayerSpirit(),
            $heroFactory->beginnerSorcerer()->withCompletedGamePlayerSpirit(),
        ]))->create();

        $campaignStop = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withSquadID($squad->id))->create();

        $sideQuest = SideQuestFactory::new()->forQuestID($campaignStop->quest_id)->create();

        collect($minionsArrays)->each(function ($minionArray) use ($sideQuest) {
            $minion = Minion::query()->where('name', '=', $minionArray['name'])->firstOrFail();
            $sideQuest->minions()->save($minion, [
                'count' => $minionArray['count']
            ]);
        });

        $sideQuestResult = SideQuestResultFactory::new()->create([
            'campaign_stop_id' => $campaignStop->id,
            'side_quest_id' => $sideQuest->id
        ]);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($sideQuestResult);
        $this->assertNotNull($sideQuestResult);
        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent);
        $events = $sideQuestResult->sideQuestEvents;
        foreach([
            SideQuestEvent::TYPE_HERO_DAMAGES_MINION,
            SideQuestEvent::TYPE_HERO_KILLS_MINION,
            SideQuestEvent::TYPE_MINION_DAMAGES_HERO
                ] as $eventType) {
            $filtered = $events->filter(function (SideQuestEvent $event) use ($eventType) {
                return $event->event_type === $eventType;
            });
            $this->assertGreaterThan(0, $filtered->count(), 'Has events of type: ' . $eventType);
        }
        $this->assertGreaterThan(15, $events->count());
        $this->assertLessThan(300, $events->count());
    }

    public function provides_a_beginner_squad_will_be_victorious_against_easy_side_quests()
    {
        return [
            'small skeleton group' => [
                'minionsArrays' => [
                    [
                        'name' => 'Skeleton Scout',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 1
                    ]
                ]
            ],
            'easy werewolves' => [
                'minionsArrays' => [
                    [
                        'name' => 'Young Werewolf',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf',
                        'count' => 1
                    ]
                ]
            ],
            'single golem' => [
                'minionsArrays' => [
                    [
                        'name' => 'Amber Golem',
                        'count' => 1
                    ]
                ]
            ],
            'couple of young vampires' => [
                'minionsArrays' => [
                    [
                        'name' => 'Young Vampire',
                        'count' => 2
                    ]
                ]
            ],
            'group of imps' => [
                'minionsArrays' => [
                    [
                        'name' => 'Gray Imp',
                        'count' => 3
                    ],
                    [
                        'name' => 'Yellow Imp',
                        'count' => 3
                    ],
                    [
                        'name' => 'Green Imp',
                        'count' => 3
                    ]
                ]
            ]
        ];
    }

//    /**
//     * @test
//     * @param $referenceID
//     * @throws \Throwable
//     * @dataProvider provides_a_beginner_squad_will_be_defeated_by_medium_difficulty_side_quests
//     */
//    public function a_beginner_squad_will_be_defeated_by_medium_difficulty_side_quests($referenceID)
//    {
//        $heroFactory = HeroFactory::new();
//        $squad = SquadFactory::new()->withHeroes(collect([
//            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
//            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit(),
//            $heroFactory->beginnerRanger()->withCompletedGamePlayerSpirit(),
//            $heroFactory->beginnerSorcerer()->withCompletedGamePlayerSpirit(),
//        ]))->create();
//
//        $campaignStop = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withSquadID($squad->id))->create();
//
//        /** @var SideQuest $sideQuest */
//        $sideQuest = SideQuest::query()->whereHas('sideQuestBlueprint', function (Builder $builder) use ($referenceID) {
//            return $builder->where('reference_id', '=', $referenceID);
//        })->first();
//
//        $sideQuestResult = SideQuestResultFactory::new()->create([
//            'campaign_stop_id' => $campaignStop->id,
//            'side_quest_id' => $sideQuest->id
//        ]);
//
//        /** @var ProcessCombatForSideQuestResult $domainAction */
//        $domainAction = app(ProcessCombatForSideQuestResult::class);
//        $sideQuestResult = $domainAction->execute($sideQuestResult);
//        $this->assertNotNull($sideQuestResult);
//        $defeatEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT)->first();
//        $this->assertNotNull($defeatEvent);
//        $events = $sideQuestResult->sideQuestEvents;
//        foreach([
//                    SideQuestEvent::TYPE_HERO_DAMAGES_MINION,
//                    SideQuestEvent::TYPE_MINION_DAMAGES_HERO,
//                    SideQuestEvent::TYPE_MINION_KILLS_HERO
//                ] as $eventType) {
//            $filtered = $events->filter(function (SideQuestEvent $event) use ($eventType) {
//                return $event->event_type === $eventType;
//            });
//            $this->assertGreaterThan(0, $filtered->count(), 'Has events of type: ' . $eventType);
//        }
//        $this->assertGreaterThan(15, $events->count());
//        $this->assertLessThan(250, $events->count());
//    }
//
//    public function provides_a_beginner_squad_will_be_defeated_by_medium_difficulty_side_quests()
//    {
//        return [
//            [
//                'referenceID' => 'H',
//            ],
//            [
//                'referenceID' => 'L',
//            ],
//            [
//                'referenceID' => 'T',
//            ],
//            [
//                'referenceID' => 'V',
//            ],
//        ];
//    }

    /**
     * @test
     */
    public function it_will_rollback_the_processed_at_any_any_side_quest_events_if_an_exception_is_thrown()
    {
        $heroFactory = HeroFactory::new();
        $squad = SquadFactory::new()->withHeroes(collect([
            $heroFactory->beginnerWarrior()->withCompletedGamePlayerSpirit()
        ]))->create();

        $campaignStop = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withSquadID($squad->id))->create();

        $sideQuestResult = SideQuestResultFactory::new()->create([
            'campaign_stop_id' => $campaignStop->id
        ]);

        $exception = new \Exception();
        $runCombatTurnMock = \Mockery::mock(RunCombatTurn::class)
            ->shouldReceive('execute')->andThrow($exception)->getMock();

        app()->instance(RunCombatTurn::class, $runCombatTurnMock);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $this->assertNull($this->sideQuestResult->combat_processed_at);
        try {
            $domainAction->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $sideQuestResult = $sideQuestResult->fresh();
            $this->assertEquals(0, $sideQuestResult->sideQuestEvents()->count());
            $this->assertNull($sideQuestResult->combat_processed_at);
            return;
        }
        $this->fail("Exception not thrown");
    }

}
