<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\NPCAction;
use App\Domain\Actions\NPC\NPCHeroAction;
use App\Facades\NPC;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class NPCHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return NPCHeroAction
     */
    abstract protected function getDomainAction();

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_is_not_an_npc()
    {
        $hero = HeroFactory::new()->create();
        NPC::shouldReceive('isNPC')->andReturn(false);

        try {
            $this->getDomainAction()->execute($hero);
        } catch (\Exception $exception) {
            $this->assertEquals(NPCAction::EXCEPTION_CODE_NOT_NPC, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
