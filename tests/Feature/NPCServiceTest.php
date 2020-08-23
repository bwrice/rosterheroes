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

    /**
     * @test
     */
    public function it_will_return_quests_with_side_quests_from_fetroya_for_a_new_npc()
    {
        $squad = SquadFactory::new()->create();

        /** @var Continent $fetroya */
        $fetroya = Continent::query()->where('name', '=', Continent::FETROYA)->first();
        $provinces = Province::query()->where('continent_id', '=', $fetroya->id)->inRandomOrder()->get();

        for ($i = 1; $i <= $squad->getQuestsPerWeek() + 1; $i++) {
            $quest = QuestFactory::new()->withProvinceID($provinces->shift()->id)->create();
            for ($j = 1; $j <= $squad->getSideQuestsPerQuest() + 1; $j++) {
                SideQuestFactory::new()->forQuestID($quest->id)->create();
            }
        }

        $questsToJoin = NPC::questsToJoin($squad);
        $this->assertEquals($squad->getQuestsPerWeek(), $questsToJoin->count());

        $questsToJoin->each(function ($questToJoinArray) use ($fetroya, $squad) {
            /** @var Quest $quest */
            $quest = $questToJoinArray['quest'];
            $this->assertEquals($fetroya->id, $quest->province->continent_id);

            $this->assertEquals($squad->getSideQuestsPerQuest(), count($questToJoinArray['side_quests']));
            foreach ($questToJoinArray['side_quests'] as $sideQuest) {
                /** @var SideQuest $sideQuest */
                $this->assertEquals($quest->id, $sideQuest->quest_id);
            }
        });
    }

    /**
     * @test
     */
    public function it_will_not_return_a_spirit_for_a_non_current_week()
    {
        $hero = HeroFactory::new()->create();
        $week = factory(Week::class)->states('as-current')->create();
        $diffWeek = factory(Week::class)->create();

        // Create valid spirit, but for a different week
        $spirit = $this->getValidPlayerSpiritForHeroAndWeek($hero, $diffWeek);
        $heroSpirit = NPC::heroSpirit($hero);

        $this->assertNull($heroSpirit);
    }

    /**
     * @test
     */
    public function it_will_not_return_a_spirit_with_an_invalid_position()
    {
        $hero = HeroFactory::new()->create();
        $week = factory(Week::class)->states('as-current')->create();

        $spirit = $this->getValidPlayerSpiritForHeroAndWeek($hero, $week);

        // Reset the player's positions to invalid ones for our hero
        $invalidPositionIDs = Position::query()->whereNotIn('id', $hero->heroRace->positions()->pluck('id')->toArray())->pluck('id')->toArray();
        $spirit->playerGameLog->player->positions()->sync($invalidPositionIDs);

        $heroSpirit = NPC::heroSpirit($hero);

        $this->assertNull($heroSpirit);
    }

    /**
     * @test
     */
    public function it_will_not_return_a_spirit_already_used_by_the_same_squad()
    {
        $hero = HeroFactory::new()->create();
        $week = factory(Week::class)->states('as-current')->create();

        $spirit = $this->getValidPlayerSpiritForHeroAndWeek($hero, $week);

        $otherSquadHero = HeroFactory::new()->forSquad($hero->squad)->create();
        $otherSquadHero->player_spirit_id = $spirit->id;
        $otherSquadHero->save();

        $heroSpirit = NPC::heroSpirit($hero);

        $this->assertNull($heroSpirit);
    }

    /**
     * @test
     */
    public function it_will_not_return_a_spirit_with_higher_essence_cost_than_npc_has_available()
    {
        $hero = HeroFactory::new()->create();
        $week = factory(Week::class)->states('as-current')->create();

        $spirit = $this->getValidPlayerSpiritForHeroAndWeek($hero, $week);
        $spirit->essence_cost = 11111;
        $spirit->save();

        // Set npc total essence and attach a spirit to another hero costing more than half of the essence
        $npc = $hero->squad;
        $npc->spirit_essence = 18000;
        $otherSquadHero = HeroFactory::new()->forSquad($npc)->create();

        $otherSquadHero->player_spirit_id = PlayerSpiritFactory::new()->withEssenceCost(11111)->create()->id;
        $otherSquadHero->save();

        $heroSpirit = NPC::heroSpirit($hero);

        $this->assertNull($heroSpirit);
    }

    /**
     * @test
     */
    public function it_will_return_a_valid_spirit_for_the_npc_hero()
    {

        $hero = HeroFactory::new()->create();
        $week = factory(Week::class)->states('as-current')->create();

        $spirit = $this->getValidPlayerSpiritForHeroAndWeek($hero, $week);

        $heroSpirit = NPC::heroSpirit($hero);

        $this->assertEquals($spirit->id, $heroSpirit->id);
    }

    protected function getValidPlayerSpiritForHeroAndWeek(Hero $hero, Week $week)
    {
        $spirit = PlayerSpiritFactory::new()->forWeek($week)->withEssenceCost('5555')->create();
        $player = $spirit->playerGameLog->player;
        $positions = $hero->heroRace->positions;
        $positionsToAttach = $positions->shuffle()->take(rand(1,3));
        $player->positions()->saveMany($positionsToAttach);
        return $spirit->fresh();
    }
}
