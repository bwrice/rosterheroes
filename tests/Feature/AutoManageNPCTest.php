<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Actions\NPC\AutoManageNPC;
use App\Domain\Actions\NPC\BuildNPCActionTrigger;
use App\Facades\NPC;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\SquadFactory;
use App\Jobs\OpenChestJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AutoManageNPCTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AutoManageNPC
     */
    protected function getDomainAction()
    {
        return app(AutoManageNPC::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_open_chests_jobs_for_an_npc()
    {
        $chests = collect();

        $chestsCount = rand(2, 5);
        for ($i = 1; $i <= $chestsCount; $i++) {
            $chests->push(ChestFactory::new()->create());
        }

        NPC::shouldReceive('isNPC')->andReturn(true);


        $npc = SquadFactory::new()->create();

        $trigger = (new NPCActionTrigger(100))->pushAction(NPCActionTrigger::KEY_OPEN_CHESTS, [
            'chests' => $chests
        ]);

        $mock = $this->getMockBuilder(BuildNPCActionTrigger::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method('execute')->willReturn($trigger);

        $this->instance(BuildNPCActionTrigger::class, $mock);

        Queue::fake();
        $this->getDomainAction()->execute($npc, 100, 120);


        // Test job arguments
        Queue::assertPushed(OpenChestJob::class, function (OpenChestJob $job) use ($chests) {
            return in_array($job->chest->id, $chests->pluck('id')->toArray());
        });

        // Test jobs chained the right amount
        $chain = [];
        for ($i = 1; $i <= ($chestsCount - 1); $i++) {
            $chain[] = OpenChestJob::class;
        }
        Queue::assertPushedWithChain(OpenChestJob::class, $chain);
    }
}
