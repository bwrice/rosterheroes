<?php

namespace Tests\Feature;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Actions\NPC\EmbodyNPCHero;
use App\Domain\Actions\NPC\NPCHeroAction;
use App\Facades\NPC;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmbodyNPCHeroTest extends NPCHeroActionTest
{

    /**
     * @return NPCHeroAction
     */
    protected function getDomainAction()
    {
        return app(EmbodyNPCHero::class);
    }

    /**
     * @test
     */
    public function it_will_execute_add_spirit_to_hero_action_with_the_hero_given_by_npc_service()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);

        $playerSpirit = PlayerSpiritFactory::new()->create();

        NPC::shouldReceive('heroSpirit')->andReturn($playerSpirit);

        $npcHero = HeroFactory::new()->create();

        $mock = \Mockery::spy(AddSpiritToHeroAction::class);

        $this->app->instance(AddSpiritToHeroAction::class, $mock);

        $this->getDomainAction()->execute($npcHero, $playerSpirit);

        $mock->shouldHaveReceived('execute')
            ->with($npcHero, $playerSpirit);
    }

    /**
     * @test
     */
    public function it_will_no_execute_add_spirit_action_if_npc_service_returns_null()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);

        $playerSpirit = PlayerSpiritFactory::new()->create();

        NPC::shouldReceive('heroSpirit')->andReturn(null);

        $npcHero = HeroFactory::new()->create();

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);

        $this->app->instance(AddSpiritToHeroAction::class, $spy);

        $this->getDomainAction()->execute($npcHero, $playerSpirit);

        $spy->shouldNotHaveReceived('execute');
    }
}
