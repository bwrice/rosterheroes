<?php

namespace Tests\Feature;

use App\Domain\Models\User;
use App\Facades\NPC;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Tests\TestCase;

class NPCServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @param $env
     * @param $diffEnv
     * @dataProvider provides_it_will_return_a_squad_name_from_the_config_based_on_environment
     */
    public function it_will_return_a_squad_name_from_the_config_based_on_environment($env, $diffEnv)
    {
        $this->app->detectEnvironment(function () use ($env) {
            return $env;
        });

        Config::set('npc.' . $env . '.squads', [
            [
                'name' => $expected = Str::random()
            ]
        ]);

        Config::set('npc.' . $diffEnv . '.squads', [
            [
                'name' => Str::random()
            ]
        ]);

        $squadName = NPC::squadName();

        $this->assertEquals($expected, $squadName);
    }

    public function provides_it_will_return_a_squad_name_from_the_config_based_on_environment()
    {
        return [
            'local' => [
                'env' => 'local',
                'diffEnv' => 'beta'
            ],
            'beta' => [
                'env' => 'beta',
                'diffEnv' => 'production'
            ],
            'production' => [
                'env' => 'production',
                'diffEnv' => 'beta'
            ]
        ];
    }

    /**
     * @test
     */
    public function it_will_return_the_npc_user()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        Config::set('npc.user.email', $user->email);

        $npcUser = NPC::user();
        $this->assertEquals($user->id, $npcUser->id);
    }
}
