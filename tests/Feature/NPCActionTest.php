<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\NPCAction;
use App\Domain\Models\Squad;
use App\Facades\NPC;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class NPCActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /**
     * @return NPCAction
     */
    abstract protected function getDomainAction();

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_an_npc()
    {
        $squad = SquadFactory::new()->create();
        NPC::shouldReceive('isNPC')->andReturn(false);

        try {
            $this->getDomainAction()->execute($squad);
        } catch (\Exception $exception) {
            $this->assertEquals(NPCAction::EXCEPTION_CODE_NOT_NPC, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
