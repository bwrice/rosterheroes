<?php

use App\Domain\Actions\Content\CreateMinion;
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
                'level' => 86,
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
                'name' => 'Vampire Marksman',
                'level' => 112,
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
                'name' => 'Vampire Elder',
                'level' => 182,
                'enemy_type' => EnemyType::VAMPIRE,
                'combat_position' => CombatPosition::BACK_LINE,
                'attacks' => [
                    'Severe Bite',
                    'Vicious Bite',
                    'Vampiric Bite',
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
        ]);

        /** @var CreateMinion $domainAction */
        $domainAction = app(CreateMinion::class);

        $minionData->each(function ($minionData) use ($domainAction) {
            $domainAction->execute($minionData['name'], $minionData['level'], $minionData['enemy_type'], $minionData['combat_position'], $minionData['attacks'], $minionData['chest_blueprints']);
        });
    }

    protected function seedSideQuestBlueprints()
    {

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
