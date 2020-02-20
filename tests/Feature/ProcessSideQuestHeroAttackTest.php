<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestHeroAttack;
use App\Domain\Combat\CombatMinion;
use App\Domain\Combat\HeroCombatAttack;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestHeroAttackTest extends TestCase
{
    use DatabaseTransactions;

    /** @var SideQuestResult */
    protected $sideQuestResult;

    /** @var HeroCombatAttack */
    protected $heroCombatAttack;

    /** @var CombatMinion */
    protected $combatMinion;

    public function setUp(): void
    {
        parent::setUp();
        $this->sideQuestResult = SideQuestResultFactory::new()->create();
        $this->heroCombatAttack = HeroCombatAttackFactory::new()->create();
        $this->combatMinion = CombatMinionFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_save_an_attack_event_if_not_blocked()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $this->combatMinion, false);
        $sideQuestEvents = $this->sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        $event = $sideQuestEvents->first();
        $this->assertEquals('attack', $event->data['type']);
    }

}
