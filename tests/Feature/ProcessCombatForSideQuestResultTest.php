<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Actions\Combat\BuildSideQuestGroup;
use App\Domain\Actions\Combat\ProcessCombatForSideQuestResult;
use App\Domain\Actions\Combat\RunCombatTurn;
use App\Domain\Actions\Snapshots\BuildMinionSnapshot;
use App\Domain\Actions\Snapshots\BuildSideQuestSnapshot;
use App\Domain\Actions\Snapshots\BuildSquadSnapshot;
use App\Domain\Combat\CombatRunner;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestSnapshot;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Factories\Models\SideQuestSnapshotFactory;
use App\Factories\Models\SquadFactory;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use App\Factories\Models\SquadSnapshotFactory;
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

    /** @var SquadSnapshot */
    protected $squadSnapshot;

    /** @var SideQuestSnapshot */
    protected $sideQuestSnapshot;

    public function setUp(): void
    {
        parent::setUp();
        $week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $campaignFactory = CampaignFactory::new()->withWeekID($week->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $this->sideQuestResult = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory)->create();
        $this->squadSnapshot = SquadSnapshotFactory::new()->create();
        $this->sideQuestSnapshot = SideQuestSnapshotFactory::new()->create();
        $this->sideQuestResult->squad_snapshot_id = $this->squadSnapshot->id;
        $this->sideQuestResult->side_quest_snapshot_id = $this->sideQuestSnapshot->id;
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
            $this->assertEquals($exception->getCode(), ProcessCombatForSideQuestResult::EXCEPTION_CODE_SIDE_QUEST_ALREADY_PROCESSED);
            $this->assertEquals($processedAt->timestamp, $this->sideQuestResult->fresh()->combat_processed_at->timestamp);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_is_no_matching_squad_snapshot()
    {
        $this->sideQuestResult->squad_snapshot_id = null;
        $this->sideQuestResult->save();

        try {
            /** @var ProcessCombatForSideQuestResult $domainAction */
            $domainAction = app(ProcessCombatForSideQuestResult::class);
            $domainAction->execute($this->sideQuestResult->fresh());
        } catch (\Exception $exception) {
            $this->assertEquals($exception->getCode(), ProcessCombatForSideQuestResult::EXCEPTION_CODE_NO_SQUAD_SNAPSHOT);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_set_the_squad_and_side_quest_snapshots_on_the_side_quest_result()
    {
        $this->sideQuestResult->side_quest_snapshot_id = null;
        $this->sideQuestResult->save();

        try {
            /** @var ProcessCombatForSideQuestResult $domainAction */
            $domainAction = app(ProcessCombatForSideQuestResult::class);
            $domainAction->execute($this->sideQuestResult->fresh());
        } catch (\Exception $exception) {
            $this->assertEquals($exception->getCode(), ProcessCombatForSideQuestResult::EXCEPTION_CODE_NO_SIDE_QUEST_SNAPSHOT);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_combat_processed_at_on_the_side_quest_result()
    {
        $maxMoments = 5;
        $runCombatTurnMock = \Mockery::mock(CombatRunner::class)
            ->shouldReceive('execute')
            ->andReturn([
                'victorious_side' => null,
                'moment' => $maxMoments
            ])
            ->getMock();

        $runCombatTurnMock->shouldReceive('registerTurnAHandler')
            ->andReturnSelf();
        $runCombatTurnMock->shouldReceive('registerTurnBHandler')
            ->andReturnSelf();

        app()->instance(CombatRunner::class, $runCombatTurnMock);

        $this->assertNull($this->sideQuestResult->combat_processed_at);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult, $maxMoments);
        $this->assertNotNull($sideQuestResult->fresh()->combat_processed_at);
    }

    /**
     * @test
     */
    public function it_will_create_side_result_with_a_battlefield_set_event()
    {
        $maxMoments = 5;
        $runCombatTurnMock = \Mockery::mock(CombatRunner::class)
            ->shouldReceive('execute')
            ->andReturn([
                'victorious_side' => null,
                'moment' => $maxMoments
            ])
            ->getMock();

        $runCombatTurnMock->shouldReceive('registerTurnAHandler')
            ->andReturnSelf();
        $runCombatTurnMock->shouldReceive('registerTurnBHandler')
            ->andReturnSelf();

        app()->instance(CombatRunner::class, $runCombatTurnMock);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult, $maxMoments);

        $sideQuestEvents = $sideQuestResult->sideQuestEvents()
            ->where('event_type', '=', SideQuestEvent::TYPE_BATTLEGROUND_SET)->get();
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $battleGroundSetEvent */
        $battleGroundSetEvent = $sideQuestEvents->first();
        $this->assertEquals(0, $battleGroundSetEvent->moment);
    }

    /**
     * @test
     * @param $eventType
     * @param $victoriousSide
     * @dataProvider provides_it_will_create_the_correct_final_side_quest_event_based_on_the_combat_runner_results
     */
    public function it_will_create_the_correct_final_side_quest_event_based_on_the_combat_runner_results($eventType, $victoriousSide)
    {
        $maxMoments = rand(2, 8);
        $runCombatTurnMock = \Mockery::mock(CombatRunner::class)
            ->shouldReceive('execute')
            ->andReturn([
                'victorious_side' => $victoriousSide,
                'moment' => $maxMoments
            ])
            ->getMock();

        $runCombatTurnMock->shouldReceive('registerTurnAHandler')
            ->andReturnSelf();
        $runCombatTurnMock->shouldReceive('registerTurnBHandler')
            ->andReturnSelf();

        app()->instance(CombatRunner::class, $runCombatTurnMock);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($this->sideQuestResult, $maxMoments);

        $sideQuestEvents = $sideQuestResult->sideQuestEvents()
            ->where('event_type', '=', $eventType)->get();
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $endEvent */
        $endEvent = $sideQuestEvents->first();
        $this->assertEquals($maxMoments, $endEvent->moment);
    }

    public function provides_it_will_create_the_correct_final_side_quest_event_based_on_the_combat_runner_results()
    {
        return [
            'victory' => [
                'eventType' => SideQuestEvent::TYPE_SIDE_QUEST_VICTORY,
                'victoriousSide' => CombatRunner::SIDE_A
            ],
            'defeat' => [
                'eventType' => SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT,
                'victoriousSide' => CombatRunner::SIDE_B
            ],
            'draw' => [
                'eventType' => SideQuestEvent::TYPE_SIDE_QUEST_DRAW,
                'victoriousSide' => null
            ],
        ];
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

        /** @var BuildMinionSnapshot $buildMinionSnapshot */
        $buildMinionSnapshot = app(BuildMinionSnapshot::class);
        collect($minionsArrays)->each(function ($minionArray) use ($sideQuest, $buildMinionSnapshot) {
            $minion = Minion::query()->where('name', '=', $minionArray['name'])->firstOrFail();
            $buildMinionSnapshot->execute($minion);
            $sideQuest->minions()->save($minion, [
                'count' => $minionArray['count']
            ]);
        });

        $sideQuestResult = SideQuestResultFactory::new()->create([
            'campaign_stop_id' => $campaignStop->id,
            'side_quest_id' => $sideQuest->id
        ]);

        /** @var BuildSquadSnapshot $buildSquadSnapshot */
        $buildSquadSnapshot = app(BuildSquadSnapshot::class);
        $squadSnapshot = $buildSquadSnapshot->execute($squad);

        /** @var BuildSideQuestSnapshot $buildSideQuestSnapshot */
        $buildSideQuestSnapshot = app(BuildSideQuestSnapshot::class);
        $sideQuestSnapshot = $buildSideQuestSnapshot->execute($sideQuest);

        $sideQuestResult->squad_snapshot_id = $squadSnapshot->id;
        $sideQuestResult->side_quest_snapshot_id = $sideQuestSnapshot->id;
        $sideQuestResult->save();

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($sideQuestResult);
        $victoryEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_VICTORY)->first();
        $this->assertNotNull($victoryEvent, "No victory event found");
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
            'single golems' => [
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
                        'count' => 2
                    ],
                    [
                        'name' => 'Green Imp',
                        'count' => 2
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @param $minionsArrays
     * @throws \Throwable
     * @dataProvider provides_a_beginner_squad_will_be_defeated_by_medium_difficulty_side_quests
     */
    public function a_beginner_squad_will_be_defeated_by_medium_difficulty_side_quests($minionsArrays)
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

        /** @var BuildMinionSnapshot $buildMinionSnapshot */
        $buildMinionSnapshot = app(BuildMinionSnapshot::class);
        collect($minionsArrays)->each(function ($minionArray) use ($sideQuest, $buildMinionSnapshot) {
            $minion = Minion::query()->where('name', '=', $minionArray['name'])->firstOrFail();
            $buildMinionSnapshot->execute($minion);
            $sideQuest->minions()->save($minion, [
                'count' => $minionArray['count']
            ]);
        });

        $sideQuestResult = SideQuestResultFactory::new()->create([
            'campaign_stop_id' => $campaignStop->id,
            'side_quest_id' => $sideQuest->id
        ]);

        /** @var BuildSquadSnapshot $buildSquadSnapshot */
        $buildSquadSnapshot = app(BuildSquadSnapshot::class);
        $squadSnapshot = $buildSquadSnapshot->execute($squad);

        /** @var BuildSideQuestSnapshot $buildSideQuestSnapshot */
        $buildSideQuestSnapshot = app(BuildSideQuestSnapshot::class);
        $sideQuestSnapshot = $buildSideQuestSnapshot->execute($sideQuest);

        $sideQuestResult->squad_snapshot_id = $squadSnapshot->id;
        $sideQuestResult->side_quest_snapshot_id = $sideQuestSnapshot->id;
        $sideQuestResult->save();

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $sideQuestResult = $domainAction->execute($sideQuestResult);
        $defeatEvent = $sideQuestResult->sideQuestEvents()->where('event_type', '=', SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT)->first();
        $this->assertNotNull($defeatEvent, "No defeat event found");
        $events = $sideQuestResult->sideQuestEvents;
        foreach([
                    SideQuestEvent::TYPE_HERO_DAMAGES_MINION,
                    SideQuestEvent::TYPE_MINION_DAMAGES_HERO,
                    SideQuestEvent::TYPE_MINION_KILLS_HERO
                ] as $eventType) {
            $filtered = $events->filter(function (SideQuestEvent $event) use ($eventType) {
                return $event->event_type === $eventType;
            });
            $this->assertGreaterThan(0, $filtered->count(), 'Has events of type: ' . $eventType);
        }
        $this->assertGreaterThan(15, $events->count());
        $this->assertLessThan(250, $events->count());
    }

    public function provides_a_beginner_squad_will_be_defeated_by_medium_difficulty_side_quests()
    {
        return [
            'medium skeleton group' => [
                'minionsArrays' => [
                    [
                        'name' => 'Skeleton Guard',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Soldier',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Archer',
                        'count' => 2
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 2
                    ]
                ]
            ],
            'medium werewolves' => [
                'minionsArrays' => [
                    [
                        'name' => 'Werewolf',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Thrasher',
                        'count' => 2
                    ],
                    [
                        'name' => 'Werewolf Mangler',
                        'count' => 2
                    ]
                ]
            ],
            'multiple golems' => [
                'minionsArrays' => [
                    [
                        'name' => 'Amber Golem',
                        'count' => 3
                    ],
                    [
                        'name' => 'Coral Golem',
                        'count' => 2
                    ]
                ]
            ],
            'medium vampires' => [
                'minionsArrays' => [
                    [
                        'name' => 'Vampire',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 2
                    ]
                ]
            ],
            'medium group of imps' => [
                'minionsArrays' => [
                    [
                        'name' => 'Gray Imp',
                        'count' => 6
                    ],
                    [
                        'name' => 'Yellow Imp',
                        'count' => 5
                    ],
                    [
                        'name' => 'Green Imp',
                        'count' => 4
                    ],
                    [
                        'name' => 'Orange Imp',
                        'count' => 3
                    ]
                ]
            ]
        ];
    }

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
            'campaign_stop_id' => $campaignStop->id,
            'squad_snapshot_id' => SquadSnapshotFactory::new()->create()->id,
            'side_quest_snapshot_id' => SideQuestSnapshotFactory::new()->create()->id,
        ]);

        $exceptionCode = rand(100, 999);
        $exception = new \Exception("test", $exceptionCode);
        $runCombatTurnMock = \Mockery::mock(CombatRunner::class)
            ->shouldReceive('execute')
            ->andThrow($exception)->getMock();

        $runCombatTurnMock->shouldReceive('registerTurnAHandler')->andReturnSelf();
        $runCombatTurnMock->shouldReceive('registerTurnBHandler')->andReturnSelf();

        app()->instance(CombatRunner::class, $runCombatTurnMock);

        /** @var ProcessCombatForSideQuestResult $domainAction */
        $domainAction = app(ProcessCombatForSideQuestResult::class);
        $this->assertNull($this->sideQuestResult->combat_processed_at);
        try {
            $domainAction->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals($exceptionCode, $exception->getCode());
            $sideQuestResult = $sideQuestResult->fresh();
            $this->assertEquals(0, $sideQuestResult->sideQuestEvents()->count());
            $this->assertNull($sideQuestResult->combat_processed_at);
            return;
        }
        $this->fail("Exception not thrown");
    }

}
