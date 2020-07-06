<?php

use App\Domain\Actions\Content\CreateMinion;
use App\Domain\Actions\Content\CreateSideQuestBlueprint;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
                'combat_position' => CombatPosition::HIGH_GROUND,
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
                'combat_position' => CombatPosition::HIGH_GROUND,
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
        $questData = collect([
            [
                'name' => 'Gabrielle Night Wing',
            ],
            [
                'name' => 'Gabrielle Blood Wing',
            ],
            [
                'name' => 'Gabrielle Death Wing',
            ],
            [
                'name' => 'North Gabrielle Castle',
            ],
            [
                'name' => 'East Gabrielle Castle',
            ],
            [
                'name' => 'Gabrielle High Castle',
            ],
        ]);
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
