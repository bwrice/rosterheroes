<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\CreateBattlefieldSetForSideQuestEvent;
use App\Factories\Combat\CombatSquadFactory;
use App\Factories\Combat\SideQuestGroupFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBattlefieldSetForSideQuestEventTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_create_a_battle_ground_set_side_quest_event()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $combatSquad = CombatSquadFactory::new()->withCombatHeroes()->create();
        $sideQuestGroup = SideQuestGroupFactory::new()->withCombatMinions()->create();

        /** @var CreateBattlefieldSetForSideQuestEvent $domainAction */
        $domainAction = app(CreateBattlefieldSetForSideQuestEvent::class);
        $domainAction->execute($sideQuestResult, $combatSquad, $sideQuestGroup);
        $sideQuestEvents = $sideQuestResult->fresh()->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $event */
        $event = $sideQuestEvents->first();
        $this->assertEquals(0, $event->moment);
        $this->assertEquals(SideQuestEvent::TYPE_BATTLEGROUND_SET, $event->event_type);
    }
}
