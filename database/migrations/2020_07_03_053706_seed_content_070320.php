<?php

use App\Domain\Actions\Content\CreateMinion;
use App\Domain\Actions\Content\CreateSideQuestBlueprint;
use App\Domain\Actions\CreateSideQuest;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuestBlueprint;
use App\Domain\Models\Titan;
use App\Domain\Models\TravelType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SeedContent070320 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->seedMinions();
        $this->seedSideQuestBlueprints();
        $this->seedQuests();
    }

    protected function seedMinions()
    {
        $minionData = collect([
            [
                'name' => 'Gargoyle',
                'level' => 56,
                'enemy_type' => EnemyType::GARGOYLE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Claw',
                    'Double Claw',
                    'Maul',
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 6
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => 'Gargoyle Flanker',
                'level' => 64,
                'enemy_type' => EnemyType::GARGOYLE,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Claw',
                    'Double Claw',
                    'Pounce',
                    'Double Pounce',
                    'Triple Pounce'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 20
                    ]
                ]
            ],
            [
                'name' => 'Gargoyle Pummeler',
                'level' => 75,
                'enemy_type' => EnemyType::GARGOYLE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Vicious Bite',
                    'Claw',
                    'Double Claw',
                    'Triple Claw',
                    'Maul'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 25
                    ]
                ]
            ],
            [
                'name' => 'Gargoyle Bludgeoner',
                'level' => 90,
                'enemy_type' => EnemyType::GARGOYLE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Vicious Bite',
                    'Claw',
                    'Double Claw',
                    'Triple Claw',
                    'Maul'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 20
                    ]
                ]
            ],
            [
                'name' => 'Gargoyle Sentinel',
                'level' => 106,
                'enemy_type' => EnemyType::GARGOYLE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Lethal Bite',
                    'Claw',
                    'Double Claw',
                    'Triple Claw',
                    'Maul'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 20
                    ]
                ]
            ],
            [
                'name' => 'Vampire Guard',
                'level' => 51,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Vicious Bite',
                    'Bolt Shot',
                    'Stab',
                    'Double Stab',
                    'Polearm Strike',
                    'Polearm Blitz'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 20
                    ]
                ]
            ],
            [
                'name' => 'Vampire Marksman',
                'level' => 72,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::HIGH_GROUND,
                'attacks' => [
                    'Severe Bite',
                    'Vicious Bite',
                    'Bolt Shot',
                    'Double Bolt Shot',
                    'Triple Bolt Release',
                    'Bolt Spray',
                    'Bolt Barrage'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 25
                    ]
                ]
            ],
            [
                'name' => 'Vampire Elder',
                'level' => 120,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Vicious Bite',
                    'Vampiric Bite',
                    'Cleave',
                    'Double Cleave',
                    'Magic Bullet',
                    'Double Magic Bullet',
                    'Triple Magic Bullet',
                    'Magic Torpedo',
                    'Blood Swell',
                    'Blood Boil'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 40
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 25
                    ]
                ]
            ],
            [
                'name' => 'Vampire Captain',
                'level' => 113,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::FRONT_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Vicious Bite',
                    'Vampiric Bite',
                    'Clobber',
                    'Double Clobber',
                    'Triple Clobber',
                    'Mace Whirlwind',
                    'Mace Tornado'
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 25
                    ]
                ]
            ],
        ]);

        /** @var CreateMinion $domainAction */
        $domainAction = app(CreateMinion::class);

        $minionData->each(function ($minionData) use ($domainAction) {
            $domainAction->execute($minionData['name'], $minionData['level'], $minionData['enemy_type'], $minionData['combat_position'], $minionData['attacks'], $minionData['chest_blueprints']);
        });
    }

    protected function seedSideQuestBlueprints()
    {
        $sideQuestBlueprintData = collect([
            [
                'name' => null,
                'reference_id' => 'AX',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 3
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AY',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'AZ',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BA',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BB',
                'minions' => [
                    [
                        'name' => 'Vampire Knight',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BC',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BD',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BE',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 5
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 50
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BF',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 3,
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 2,
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BG',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BH',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 2,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BI',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BJ',
                'minions' => [
                    [
                        'name' => 'Vampire Knight',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BK',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 5
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 2,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BL',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 2,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BM',
                'minions' => [
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 5
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 3
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 50
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BN',
                'minions' => [
                    [
                        'name' => 'Gargoyle',
                        'count' => 3
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BO',
                'minions' => [
                    [
                        'name' => 'Gargoyle',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 25
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BP',
                'minions' => [
                    [
                        'name' => 'Gargoyle',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 30
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BQ',
                'minions' => [
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 15
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BR',
                'minions' => [
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 65
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 20
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BS',
                'minions' => [
                    [
                        'name' => 'Gargoyle',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BT',
                'minions' => [
                    [
                        'name' => 'Vampire Knight',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_2,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BU',
                'minions' => [
                    [
                        'name' => 'Young Vampire',
                        'count' => 3
                    ]
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 5
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BV',
                'minions' => [
                    [
                        'name' => 'Young Vampire',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire',
                        'count' => 1
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 40
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 12
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => null,
                'reference_id' => 'BW',
                'minions' => [
                    [
                        'name' => 'Young Vampire',
                        'count' => 5
                    ],
                    [
                        'name' => 'Vampire',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::FULLY_RANDOM_TINY,
                        'count' => 1,
                        'chance' => 13
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Gate House",
                'reference_id' => 'BX',
                'minions' => [
                    [
                        'name' => 'Vampire Guard',
                        'count' => 5
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 8
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 12
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Bailey",
                'reference_id' => 'BY',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Elder',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LARGE_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Outer Bailey",
                'reference_id' => 'BZ',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Elder',
                        'count' => 1
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 5
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Sentinel',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LARGE_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_HIGH_TIER_RANDOM,
                        'count' => 3,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Inner Bailey",
                'reference_id' => 'CA',
                'minions' => [
                    [
                        'name' => 'Vampire Magus',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 8
                    ],
                    [
                        'name' => 'Vampire Elder',
                        'count' => 2
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 6
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'count' => 6
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle Sentinel',
                        'count' => 3
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::VERY_LARGE_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_HIGH_TIER_RANDOM,
                        'count' => 5,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 3,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Keep",
                'reference_id' => 'CB',
                'minions' => [
                    [
                        'name' => 'Vampire Magus',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 16
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 12
                    ],
                    [
                        'name' => 'Vampire Elder',
                        'count' => 4
                    ],
                    [
                        'name' => 'Gargoyle',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 3
                    ],
                    [
                        'name' => 'Gargoyle Sentinel',
                        'count' => 2
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::VERY_LARGE_HIGH_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_HIGH_TIER_RANDOM,
                        'count' => 5,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_5,
                        'count' => 2,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Throne Room",
                'reference_id' => 'CC',
                'minions' => [
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 16
                    ],
                    [
                        'name' => 'Vampire Elder',
                        'count' => 9
                    ],
                    [
                        'name' => 'Gargoyle Sentinel',
                        'count' => 12
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::VERY_LARGE_HIGH_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_HIGH_TIER_RANDOM,
                        'count' => 4,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_7,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Wall Walk",
                'reference_id' => 'CD',
                'minions' => [
                    [
                        'name' => 'Vampire Guard',
                        'count' => 7
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'count' => 3
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 4
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::TINY_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_3,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Castle Dungeon",
                'reference_id' => 'CE',
                'minions' => [
                    [
                        'name' => 'Vampire Guard',
                        'count' => 8
                    ],
                    [
                        'name' => 'Werewolf Ravager',
                        'count' => 3
                    ],
                    [
                        'name' => 'Werewolf Mauler',
                        'count' => 4
                    ],
                    [
                        'name' => 'Werewolf Maimer',
                        'count' => 7
                    ],
                    [
                        'name' => 'Werewolf Eviscerator',
                        'count' => 5
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::SMALL_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 2,
                        'chance' => 30
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_4,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Castle Cemetery",
                'reference_id' => 'CF',
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'count' => 8
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'count' => 3
                    ],
                    [
                        'name' => 'Skeleton Mage',
                        'count' => 5
                    ],
                    [
                        'name' => 'Skeleton Battler',
                        'count' => 3
                    ],
                    [
                        'name' => 'Lich',
                        'count' => 8
                    ],
                    [
                        'name' => 'Lich Mage',
                        'count' => 4
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::SMALL_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::MEDIUM_MID_TIER_RANDOM,
                        'count' => 3,
                        'chance' => 30
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_5,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
            [
                'name' => "Vampire Squadron",
                'reference_id' => 'CG',
                'minions' => [
                    [
                        'name' => 'Vampire Knight',
                        'count' => 8
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'count' => 6
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'count' => 6
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'count' => 5
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'count' => 5
                    ],
                    [
                        'name' => 'Gargoyle Sentinel',
                        'count' => 2
                    ],
                    [
                        'name' => 'Vampire Captain',
                        'count' => 1
                    ],
                ],
                'chest_blueprints' => [
                    [
                        'reference_id' => ChestBlueprint::LARGE_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 100
                    ],
                    [
                        'reference_id' => ChestBlueprint::VERY_LARGE_HIGH_TIER_RANDOM,
                        'count' => 1,
                        'chance' => 8
                    ],
                    [
                        'reference_id' => ChestBlueprint::GOLD_ONLY_LEVEL_5,
                        'count' => 1,
                        'chance' => 100
                    ]
                ]
            ],
        ]);

        /** @var CreateSideQuestBlueprint $action */
        $action = app(CreateSideQuestBlueprint::class);

        $sideQuestBlueprintData->each(function ($blueprintData) use ($action) {
            $action->execute(
                $blueprintData['name'],
                $blueprintData['reference_id'],
                $blueprintData['minions'],
                $blueprintData['chest_blueprints']
            );
        });
    }

    protected function seedQuests()
    {
        $quests = collect([
            [
                'name' => 'Fetroya Vampire Night Wing',
                'travel_type' => TravelType::CONTINENT,
                'level' => 297,
                'starting_province' => 'Moskia',
                'titans' => [
                    [
                        'name' => 'Vampire Noble',
                        'count' => 3
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Young Vampire',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'weight' => 8
                    ],
                    [
                        'name' => 'Vampire Captain',
                        'weight' => 2
                    ],
                    [
                        'name' => 'Gargoyle',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'weight' => 5
                    ],
                ],
                'side_quest_blueprints' => [
                    'AX',
                    'AY',
                    'AZ',
                    'BB',
                    'BC',
                    'BE',
                    'BF',
                    'BH',
                    'BI',
                    'BK',
                    'BL',
                    'BN',
                    'BO',
                    'BQ',
                    'BR',
                    'BT',
                    'BU',
                    'BV',
                    'CG'
                ]
            ],
            [
                'name' => 'Fetroya Vampire Blood Wing',
                'travel_type' => TravelType::CONTINENT,
                'level' => 309,
                'starting_province' => 'Keplyos',
                'titans' => [
                    [
                        'name' => 'Vampire Noble',
                        'count' => 4
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Young Vampire',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Captain',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Gargoyle',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'weight' => 8
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'weight' => 7
                    ],
                ],
                'side_quest_blueprints' => [
                    'AX',
                    'AY',
                    'AZ',
                    'BA',
                    'BC',
                    'BD',
                    'BE',
                    'BF',
                    'BG',
                    'BI',
                    'BJ',
                    'BL',
                    'BM',
                    'BO',
                    'BP',
                    'BQ',
                    'BR',
                    'BS',
                    'BU',
                    'BV',
                    'BW',
                    'CG'
                ]
            ],
            [
                'name' => 'Fetroya Vampire Death Wing',
                'travel_type' => TravelType::CONTINENT,
                'level' => 359,
                'starting_province' => 'Padrana',
                'titans' => [
                    [
                        'name' => 'Vampire Noble',
                        'count' => 6
                    ]
                ],
                'minions' => [
                    [
                        'name' => 'Vampire',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Vampire Veteran',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Captain',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Gargoyle',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'weight' => 8
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'weight' => 7
                    ],
                ],
                'side_quest_blueprints' => [
                    'AX',
                    'AY',
                    'BA',
                    'BB',
                    'BC',
                    'BD',
                    'BE',
                    'BF',
                    'BG',
                    'BH',
                    'BJ',
                    'BK',
                    'BM',
                    'BN',
                    'BP',
                    'BQ',
                    'BR',
                    'BS',
                    'BT',
                    'BU',
                    'BW',
                    'CG'
                ]
            ],
            [
                'name' => 'North Fetroya Vampire Castle',
                'travel_type' => TravelType::STATIONARY,
                'level' => 345,
                'starting_province' => 'Rusceron',
                'titans' => [
                    [
                        'name' => 'Vampire Noble',
                        'count' => 4
                    ],
                    [
                        'name' => 'Vampire Lord',
                        'count' => 1
                    ],
                ],
                'minions' => [
                    [
                        'name' => 'Vampire Guard',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Gargoyle',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'weight' => 5
                    ],
                ],
                'side_quest_blueprints' => [
                    'AX',
                    'AY',
                    'BB',
                    'BC',
                    'BD',
                    'BE',
                    'BF',
                    'BG',
                    'BJ',
                    'BK',
                    'BL',
                    'BM',
                    'BN',
                    'BO',
                    'BP',
                    'BR',
                    'BS',
                    'BU',
                    'BV',
                    'BX',
                    'BY',
                    'CB',
                    'CD',
                    'CE',
                    'CF'
                ]
            ],
            [
                'name' => 'South Fetroya Vampire Castle',
                'travel_type' => TravelType::STATIONARY,
                'level' => 371,
                'starting_province' => 'Eoflor',
                'titans' => [
                    [
                        'name' => 'Vampire Noble',
                        'count' => 5
                    ],
                    [
                        'name' => 'Vampire Lord',
                        'count' => 2
                    ],
                ],
                'minions' => [
                    [
                        'name' => 'Vampire Guard',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Gargoyle',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'weight' => 10
                    ],
                ],
                'side_quest_blueprints' => [
                    'AX',
                    'AY',
                    'AZ',
                    'BB',
                    'BC',
                    'BD',
                    'BF',
                    'BG',
                    'BH',
                    'BK',
                    'BL',
                    'BM',
                    'BN',
                    'BO',
                    'BP',
                    'BQ',
                    'BS',
                    'BV',
                    'BW',
                    'BX',
                    'BY',
                    'CB',
                    'CD',
                    'CE',
                    'CF'
                ]
            ],
            [
                'name' => 'Fetroya Vampire High Castle',
                'travel_type' => TravelType::STATIONARY,
                'level' => 411,
                'starting_province' => 'Trovubar',
                'titans' => [
                    [
                        'name' => 'Vampire Noble',
                        'count' => 7
                    ],
                    [
                        'name' => 'Vampire Lord',
                        'count' => 3
                    ],
                ],
                'minions' => [
                    [
                        'name' => 'Vampire Guard',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Vampire Marksman',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Magus',
                        'weight' => 15
                    ],
                    [
                        'name' => 'Vampire Knight',
                        'weight' => 20
                    ],
                    [
                        'name' => 'Vampire Elder',
                        'weight' => 5
                    ],
                    [
                        'name' => 'Gargoyle Flanker',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Pummeler',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Bludgeoner',
                        'weight' => 10
                    ],
                    [
                        'name' => 'Gargoyle Sentinel',
                        'weight' => 5
                    ],
                ],
                'side_quest_blueprints' => [
                    'AZ',
                    'BA',
                    'BC',
                    'BE',
                    'BF',
                    'BH',
                    'BI',
                    'BK',
                    'BL',
                    'BM',
                    'BO',
                    'BP',
                    'BQ',
                    'BR',
                    'BS',
                    'BT',
                    'BV',
                    'BX',
                    'BZ',
                    'CA',
                    'CB',
                    'CC',
                    'CD',
                    'CE',
                    'CF'
                ]
            ],
        ]);



        $minions = Minion::all();
        $titans = Titan::all();
        $sideQuestBlueprints = SideQuestBlueprint::all();
        $provinces = Province::all();
        $travelTypes = TravelType::all();

        $quests->each(function ($questData) {
            $weightSum = collect($questData['minions'])->sum(function ($minionData) {
                return $minionData['weight'];
            });
            if ($weightSum != 100) {
                throw new RuntimeException("Minion weight of " . $weightSum . " does not equal 100 for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($minions) {
            $minionNames = array_map(function ($minionData) {
                return $minionData['name'];
            }, $questData['minions']);

            $minionsToAttach = $minions->whereIn('name', $minionNames);
            if ($minionsToAttach->count() != count($minionNames)) {
                throw new RuntimeException("Couldn't find all the minions for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($titans) {
            $titanNames = array_map(function ($minionData) {
                return $minionData['name'];
            }, $questData['titans']);

            $titansToAttach = $titans->whereIn('name', $titanNames);
            if ($titansToAttach->count() != count($titanNames)) {
                throw new RuntimeException("Couldn't find all the titans for quest: " . $questData['name']);
            }
        });

        $quests->each(function ($questData) use ($sideQuestBlueprints) {

            $blueprintsForQuest = $sideQuestBlueprints->whereIn('reference_id', $questData['side_quest_blueprints']);
            if ($blueprintsForQuest->count() != count($questData['side_quest_blueprints'])) {

                $referenceIDs = $blueprintsForQuest->pluck('reference_id')->toArray();
                $missing = collect($questData['side_quest_blueprints'])->reject(function ($referenceID) use ($referenceIDs) {
                    return in_array($referenceID, $referenceIDs);
                });
                throw new RuntimeException("Couldn't find all the side-quests for quest: " . $questData['name'] . ' : ' . print_r($missing->toArray(), true));
            }
        });

        $quests->each(function ($questData) use ($provinces) {
            $province = $provinces->firstWhere('name', $questData['starting_province']);
            if (! $province) {
                throw new RuntimeException("Couldn't find province for quest: " . $questData['name']);
            }
        });

        /** @var CreateSideQuest $createSideQuestAction */
        $createSideQuestAction = app(CreateSideQuest::class);
        $quests->each(function ($questData) use ($sideQuestBlueprints, $minions, $titans, $provinces, $travelTypes, $createSideQuestAction) {

            $provinceID = $provinces->firstWhere('name', $questData['starting_province'])->id;

            /** @var Quest $quest */
            $quest = Quest::query()->create([
                'uuid' => Str::uuid(),
                'name' => $questData['name'],
                'level' => $questData['level'],
                'percent' => 100,
                'province_id' => $provinceID,
                'initial_province_id' => $provinceID,
                'travel_type_id' => $travelTypes->firstWhere('name', $questData['travel_type'])->id,
            ]);

            collect($questData['minions'])->each(function ($minionData) use ($quest, $minions) {
                $minion = $minions->firstWhere('name', $minionData['name']);
                $quest->minions()->save($minion, ['weight' => $minionData['weight']]);
            });

            collect($questData['titans'])->each(function ($titanData) use ($quest, $titans) {
                $titan = $titans->firstWhere('name', $titanData['name']);
                $quest->titans()->save($titan, ['count' => $titanData['count']]);
            });

            $blueprintsForQuest = $sideQuestBlueprints->whereIn('reference_id', $questData['side_quest_blueprints']);
            $blueprintsForQuest->each(function (SideQuestBlueprint $sideQuestBlueprint) use ($quest, $createSideQuestAction) {
                $createSideQuestAction->execute($sideQuestBlueprint, $quest);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
