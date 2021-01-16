<?php

namespace Tests\Feature;

use App\Domain\Models\Continent;
use App\Domain\Models\Hero;
use App\Domain\Models\Position;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use App\Facades\NPC;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
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
    public function it_will_not_return_a_squad_name_already_in_use()
    {
        $this->app->detectEnvironment(function () {
            return 'development';
        });
        $npcArrays = [];
        for ($i = 1; $i <= 10; $i++) {
            $npcArrays[]['name'] = SquadFactory::new()->create()->name;
        }

        $unusedName = Str::random();
        $npcArrays[]['name'] = $unusedName;

        Config::set('npc.development.squads', $npcArrays);

        $squadName = NPC::squadName();
        $this->assertEquals($unusedName, $squadName);
    }

    /**
     * @test
     */
    public function it_will_return_a_hero_name_associated_to_the_squad_based_on_the_config()
    {
        $squad = SquadFactory::new()->create();
        $expectedHeroNames = [
            Str::random(),
            Str::random(),
            Str::random(),
            Str::random(),
        ];

        Config::set('npc.testing.squads', [
            [
                'name' => Str::random(),
                'heroes' => [
                    Str::random(),
                    Str::random(),
                    Str::random(),
                    Str::random()
                ]
            ],
            [
                'name' => $squad->name,
                'heroes' => $expectedHeroNames
            ],
            [
                'name' => Str::random(),
                'heroes' => [
                    Str::random(),
                    Str::random(),
                    Str::random(),
                    Str::random()
                ]
            ],
            [
                'name' => Str::random(),
                'heroes' => [
                    Str::random(),
                    Str::random(),
                    Str::random(),
                    Str::random()
                ]
            ],
        ]);

        $heroName = NPC::heroName($squad);
        $this->assertTrue(in_array($heroName, $expectedHeroNames));
    }

    /**
     * @test
     */
    public function it_will_return_a_hero_name_not_already_used()
    {

        $squad = SquadFactory::new()->create();
        $heroFactory = HeroFactory::new()->forSquad($squad);
        $heroNames = [
            $heroFactory->create()->name,
            $expectedName = Str::random(),
            $heroFactory->create()->name,
            $heroFactory->create()->name
        ];

        Config::set('npc.testing.squads', [
            [
                'name' => $squad->name,
                'heroes' => $heroNames
            ],
        ]);

        $heroName = NPC::heroName($squad);
        $this->assertEquals($expectedName, $heroName);
    }

    /**
     * @test
     */
    public function it_will_add_character_if_no_available_hero_names()
    {
        $squad = SquadFactory::new()->create();
        $hero = HeroFactory::new()->forSquad($squad)->create([
            'name' => Str::random(15)
        ]);
        $heroNames = [
            $alreadyUsedName = $hero->name,
        ];

        Config::set('npc.testing.squads', [
            [
                'name' => $squad->name,
                'heroes' => $heroNames
            ],
        ]);

        $heroName = NPC::heroName($squad);
        $this->assertEquals($alreadyUsedName, substr($heroName, 0, -1));
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
