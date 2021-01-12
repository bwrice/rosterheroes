<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\OpenNPCChest;
use App\Domain\Actions\OpenChest;
use App\Domain\Models\Chest;
use App\Facades\NPC;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpenNPCChestTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return OpenNPCChest
     */
    protected function getDomainAction()
    {
        return app(OpenNPCChest::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_is_no_unopened_chest()
    {
        $npc = SquadFactory::new()->create();
        $openedChest = ChestFactory::new()->withSquadID($npc->id)->opened()->create();

        NPC::shouldReceive('isNPC')->andReturn(true);

        try {
            $this->getDomainAction()->execute($npc);
        } catch (\Exception $exception) {
            $this->assertTrue(true);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_execute_open_chest_on_an_unopened_chest_for_the_npc()
    {
        $npc = SquadFactory::new()->create();
        $openedChest = ChestFactory::new()->withSquadID($npc->id)->opened()->create();
        $chest = ChestFactory::new()->withSquadID($npc->id)->create();
        $this->assertNull($chest->opened_at);

        NPC::shouldReceive('isNPC')->andReturn(true);

        $mock = $this->getMockBuilder(OpenChest::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method('execute')->with($this->callback(function (Chest $chestArg) use ($chest) {
            return $chest->id === $chest->id;
        }));

        $this->instance(OpenChest::class, $mock);

        $this->getDomainAction()->execute($npc);
    }
}
