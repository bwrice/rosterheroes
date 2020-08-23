<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\CreateNPCHero;
use App\Domain\Actions\NPC\CreateNPCSquad;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Facades\NPC;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateNPCSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return CreateNPCSquad
     */
    protected function getDomainAction()
    {
        return app(CreateNPCSquad::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_squad_with_the_npc_user_id()
    {
        $user = factory(User::class)->create();

        NPC::partialMock()->shouldReceive('user')->andReturn($user);
        NPC::partialMock()->shouldReceive('squadName')->andReturn(Str::random());
        $mock = \Mockery::mock(CreateNPCHero::class)->shouldReceive('execute')->getMock();
        $this->app->instance(CreateNPCHero::class, $mock);

        $squad = $this->getDomainAction()->execute();
        $this->assertEquals($user->id, $squad->user_id);
    }

    /**
     * @test
     */
    public function it_will_creat_a_squad_using_one_of_the_npc_squad_names()
    {
        $user = factory(User::class)->create();

        NPC::partialMock()->shouldReceive('user')->andReturn($user);

        $squadName = Str::random();

        NPC::partialMock()->shouldReceive('squadName')->andReturn($squadName);

        $mock = \Mockery::mock(CreateNPCHero::class)->shouldReceive('execute')->getMock();
        $this->app->instance(CreateNPCHero::class, $mock);

        $squad = $this->getDomainAction()->execute();
        $this->assertEquals($squadName, $squad->name);
    }

    /**
     * @test
     */
    public function it_will_execute_create_npc_heroes_with_the_created_squad()
    {
        $user = factory(User::class)->create();

        NPC::partialMock()->shouldReceive('user')->andReturn($user);

        $squadName = Str::random();

        NPC::partialMock()->shouldReceive('squadName')->andReturn($squadName);

        $mock = \Mockery::mock(CreateNPCHero::class)
            ->shouldReceive('execute')
            ->times(Squad::getStartingHeroesCount())
            ->getMock();

        $this->app->instance(CreateNPCHero::class, $mock);

        $this->getDomainAction()->execute();
    }
}
