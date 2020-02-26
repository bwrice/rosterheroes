<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestMinionAttack;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\MinionCombatAttackFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestMinionAttackTest extends TestCase
{
    /**
     * @test
     * @param $heroCurrentHealth
     * @param $block
     * @param $eventType
     * @dataProvider provides_it_will_properly_save_side_quest_events_for_minion_attacks_hero_events
     */
    public function it_will_properly_save_side_quest_events_for_minion_attacks_hero_events($heroCurrentHealth, $block, $eventType)
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $minionCombatAttack = MinionCombatAttackFactory::new()->create();
        $combatHero = CombatHeroFactory::new()->create();
        $damageReceived = rand(10, 100);
        $moment = rand(1, 99);


        $combatHeroMock = \Mockery::mock($combatHero)->shouldReceive('getCurrentHealth')->andReturn($heroCurrentHealth)->getMock();
        /** @var ProcessSideQuestMinionAttack $domainAction */
        $domainAction = app(ProcessSideQuestMinionAttack::class);
        $domainAction->execute($sideQuestResult, $moment, $damageReceived, $minionCombatAttack, $combatHeroMock, $block);

        $sideQuestEvents = $sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $sideQuestEvent */
        $sideQuestEvent = $sideQuestEvents->first();
        $this->assertEquals($moment, $sideQuestEvent->moment);
        $this->assertEquals($eventType, $sideQuestEvent->event_type);
        $this->assertEquals($combatHero->getHeroUuid(), $sideQuestEvent->data['heroUuid']);
        $this->assertEquals($minionCombatAttack->getMinionUuid(), $sideQuestEvent->data['minionUuid']);
        $this->assertEquals($minionCombatAttack->getCombatAttack()->getAttackUuid(), $sideQuestEvent->data['attackUuid']);
        if (!$block) {
            $this->assertEquals($damageReceived, $sideQuestEvent->data['damage']);
        }
    }

    public function provides_it_will_properly_save_side_quest_events_for_minion_attacks_hero_events()
    {
        return [
            'block' => [
                'heroCurrentHealth' => 100,
                'block' => true,
                'eventType' => SideQuestEvent::TYPE_HERO_BLOCKS_MINION
            ],
            'damage' => [
                'heroCurrentHealth' => 100,
                'block' => false,
                'eventType' => SideQuestEvent::TYPE_MINION_DAMAGES_HERO
            ],
            'kill' => [
                'heroCurrentHealth' => 0,
                'block' => false,
                'eventType' => SideQuestEvent::TYPE_MINION_KILLS_HERO
            ]
        ];
    }


}
