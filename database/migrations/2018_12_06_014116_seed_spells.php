<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSpells extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $measurableTypes = \App\MeasurableType::all();

        $measurableGroups = [
            [
                'name' => \App\MeasurableGroup::ATTRIBUTE,
                'types' => [
                    [
                        'name' => \App\MeasurableType::STRENGTH,
                        'spells' => [
                            [
                                'name' => 'Muscle',
                                'level' => 1
                            ],
                            [
                                'name' => 'Brute',
                                'level' => 2
                            ],
                            [
                                'name' => 'Might',
                                'level' => 3
                            ],
                            [
                                'name' => 'Physique',
                                'level' => 4
                            ],
                            [
                                'name' => 'Brawn',
                                'level' => 5
                            ],
                            [
                                'name' => 'Force',
                                'level' => 6
                            ],
                            [
                                'name' => 'Power',
                                'level' => 7
                            ],
                            [
                                'name' => 'Hercules',
                                'level' => 8
                            ],
                            [
                                'name' => 'Bia',
                                'level' => 9
                            ],
                            [
                                'name' => 'Kratos',
                                'level' => 10
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::VALOR,
                        'spells' => [
                            [
                                'name' => 'Boldness',
                                'level' => 1
                            ],
                            [
                                'name' => 'Moxie',
                                'level' => 2
                            ],
                            [
                                'name' => 'Nerve',
                                'level' => 3
                            ],
                            [
                                'name' => 'Fortitude',
                                'level' => 4
                            ],
                            [
                                'name' => 'Courage',
                                'level' => 5
                            ],
                            [
                                'name' => 'Valiance',
                                'level' => 6
                            ],
                            [
                                'name' => 'Spirit',
                                'level' => 7
                            ],
                            [
                                'name' => 'Heroism',
                                'level' => 8
                            ],
                            [
                                'name' => 'Nerio',
                                'level' => 9
                            ],
                            [
                                'name' => 'Ares',
                                'level' => 10
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::AGILITY,
                        'spells' => [
                            [
                                'name' => 'Quickness',
                                'level' => 1
                            ],
                            [
                                'name' => 'Swiftness',
                                'level' => 2
                            ],
                            [
                                'name' => 'Fleetness',
                                'level' => 3
                            ],
                            [
                                'name' => 'Coordination',
                                'level' => 4
                            ],
                            [
                                'name' => 'Nimbleness',
                                'level' => 5
                            ],
                            [
                                'name' => 'Spryness',
                                'level' => 6
                            ],
                            [
                                'name' => 'Dexterity',
                                'level' => 7
                            ],
                            [
                                'name' => 'Celerity',
                                'level' => 8
                            ],
                            [
                                'name' => 'Alacrity',
                                'level' => 9
                            ],
                            [
                                'name' => 'Hermes',
                                'level' => 10
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::FOCUS,
                        'spells' => [
                            [
                                'name' => 'Alertness',
                                'level' => 1
                            ],
                            [
                                'name' => 'Sharpness',
                                'level' => 2
                            ],
                            [
                                'name' => 'Cleverness',
                                'level' => 3
                            ],
                            [
                                'name' => 'Polish',
                                'level' => 4
                            ],
                            [
                                'name' => 'Acuteness',
                                'level' => 5
                            ],
                            [
                                'name' => 'Cunning',
                                'level' => 6
                            ],
                            [
                                'name' => 'Finesse',
                                'level' => 7
                            ],
                            [
                                'name' => 'Savvy',
                                'level' => 8
                            ],
                            [
                                'name' => 'The Hawk',
                                'level' => 9
                            ],
                            [
                                'name' => 'The Artist',
                                'level' => 10
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::APTITUDE,
                        'spells' => [
                            [
                                'name' => 'Competence',
                                'level' => 1
                            ],
                            [
                                'name' => 'Skill',
                                'level' => 2
                            ],
                            [
                                'name' => 'Ability',
                                'level' => 3
                            ],
                            [
                                'name' => 'Method',
                                'level' => 4
                            ],
                            [
                                'name' => 'The Gifted',
                                'level' => 5
                            ],
                            [
                                'name' => 'Talent',
                                'level' => 6
                            ],
                            [
                                'name' => 'Knowledge',
                                'level' => 7
                            ],
                            [
                                'name' => 'Proficiency',
                                'level' => 8
                            ],
                            [
                                'name' => 'Mastery',
                                'level' => 9
                            ],
                            [
                                'name' => 'Virtuosity',
                                'level' => 10
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::INTELLIGENCE,
                        'spells' => [
                            [
                                'name' => 'Sense',
                                'level' => 1
                            ],
                            [
                                'name' => 'Reason',
                                'level' => 2
                            ],
                            [
                                'name' => 'Capacity',
                                'level' => 3
                            ],
                            [
                                'name' => 'The Mind',
                                'level' => 4
                            ],
                            [
                                'name' => 'Acumen',
                                'level' => 5
                            ],
                            [
                                'name' => 'Wisdom',
                                'level' => 6
                            ],
                            [
                                'name' => 'Brilliance',
                                'level' => 7
                            ],
                            [
                                'name' => 'Genius',
                                'level' => 8
                            ],
                            [
                                'name' => 'Vision',
                                'level' => 9
                            ],
                            [
                                'name' => 'Athena',
                                'level' => 10
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => \App\MeasurableGroup::RESOURCE,
                'types' => [
                    [
                        'name' => \App\MeasurableType::HEALTH,
                        'spells' => [
                            [
                                'name' => 'Well-Being',
                                'level' => 1
                            ],
                            [
                                'name' => 'Fettle',
                                'level' => 2
                            ],
                            [
                                'name' => 'Vigor',
                                'level' => 3
                            ],
                            [
                                'name' => 'Hardiness',
                                'level' => 4
                            ],
                            [
                                'name' => 'Constitution',
                                'level' => 5
                            ],
                            [
                                'name' => 'Robustness',
                                'level' => 6
                            ],
                            [
                                'name' => 'Salubrity',
                                'level' => 7
                            ],
                            [
                                'name' => 'Vitality',
                                'level' => 8
                            ],
                            [
                                'name' => 'Hygieia',
                                'level' => 9
                            ],
                            [
                                'name' => 'Salus',
                                'level' => 10
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::STAMINA,
                        'spells' => [
                            [
                                'name' => 'Resolve',
                                'level' => 1
                            ],
                            [
                                'name' => 'Tolerance',
                                'level' => 2
                            ],
                            [
                                'name' => 'Firmness',
                                'level' => 3
                            ],
                            [
                                'name' => 'Endurance',
                                'level' => 4
                            ],
                            [
                                'name' => 'Dedication',
                                'level' => 5
                            ],
                            [
                                'name' => 'Resilience',
                                'level' => 6
                            ],
                            [
                                'name' => 'Determination',
                                'level' => 7
                            ],
                            [
                                'name' => 'Tenacity',
                                'level' => 8
                            ],
                            [
                                'name' => 'Perseverance',
                                'level' => 9
                            ],
                            [
                                'name' => 'Nike',
                                'level' => 10
                            ]
                        ]
                    ],
                ]
            ],
            [
                'name' => \App\MeasurableGroup::QUALITY,
                'types' => [
                    [
                        'name' => \App\MeasurableType::PASSION,
                        'spells' => [
                            [
                                'name' => 'Push',
                                'level' => 1
                            ],
                            [
                                'name' => 'Thirst',
                                'level' => 2
                            ],
                            [
                                'name' => 'Enthusiasm',
                                'level' => 3
                            ],
                            [
                                'name' => 'Appetite',
                                'level' => 4
                            ],
                            [
                                'name' => 'Excitement',
                                'level' => 5
                            ],
                            [
                                'name' => 'Initiative',
                                'level' => 6
                            ],
                            [
                                'name' => 'Hunger',
                                'level' => 7
                            ],
                            [
                                'name' => 'Eagerness',
                                'level' => 8
                            ],
                            [
                                'name' => 'Zeal',
                                'level' => 9
                            ],
                            [
                                'name' => 'Striving',
                                'level' => 10
                            ],
                            [
                                'name' => 'Aspiration',
                                'level' => 11
                            ],
                            [
                                'name' => 'Desire',
                                'level' => 12
                            ],
                            [
                                'name' => 'Drive',
                                'level' => 13
                            ],
                            [
                                'name' => 'Ardor',
                                'level' => 14
                            ],
                            [
                                'name' => 'Inspiration',
                                'level' => 15
                            ],
                            [
                                'name' => 'Fervor',
                                'level' => 16
                            ],
                            [
                                'name' => 'Hope',
                                'level' => 17
                            ],
                            [
                                'name' => 'Ambition',
                                'level' => 18
                            ],
                            [
                                'name' => 'Creativity',
                                'level' => 19
                            ],
                            [
                                'name' => 'Imagination',
                                'level' => 20
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::BALANCE,
                        'spells' => [
                            [
                                'name' => 'Calm',
                                'level' => 1
                            ],
                            [
                                'name' => 'Relaxation',
                                'level' => 2
                            ],
                            [
                                'name' => 'Composure',
                                'level' => 3
                            ],
                            [
                                'name' => 'Neutrality',
                                'level' => 4
                            ],
                            [
                                'name' => 'Grace',
                                'level' => 5
                            ],
                            [
                                'name' => 'Stasis',
                                'level' => 6
                            ],
                            [
                                'name' => 'Patience',
                                'level' => 7
                            ],
                            [
                                'name' => 'Restraint',
                                'level' => 8
                            ],
                            [
                                'name' => 'Symmetry',
                                'level' => 9
                            ],
                            [
                                'name' => 'Equilibrium',
                                'level' => 10
                            ],
                            [
                                'name' => 'Unity',
                                'level' => 11
                            ],
                            [
                                'name' => 'Empathy',
                                'level' => 12
                            ],
                            [
                                'name' => 'Ataraxia',
                                'level' => 13
                            ],
                            [
                                'name' => 'Zen',
                                'level' => 14
                            ],
                            [
                                'name' => 'Tranquility',
                                'level' => 15
                            ],
                            [
                                'name' => 'Love',
                                'level' => 16
                            ],
                            [
                                'name' => 'Peace',
                                'level' => 17
                            ],
                            [
                                'name' => 'Serenity',
                                'level' => 18
                            ],
                            [
                                'name' => 'Harmony',
                                'level' => 19
                            ],
                            [
                                'name' => 'Divinity',
                                'level' => 20
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::HONOR,
                        'spells' => [
                            [
                                'name' => 'Decency',
                                'level' => 1
                            ],
                            [
                                'name' => 'Fealty',
                                'level' => 2
                            ],
                            [
                                'name' => 'Dignity',
                                'level' => 3
                            ],
                            [
                                'name' => 'Obligation',
                                'level' => 4
                            ],
                            [
                                'name' => 'Devotion',
                                'level' => 5
                            ],
                            [
                                'name' => 'Allegiance',
                                'level' => 6
                            ],
                            [
                                'name' => 'Righteousness',
                                'level' => 7
                            ],
                            [
                                'name' => 'Morality',
                                'level' => 8
                            ],
                            [
                                'name' => 'Rectitude',
                                'level' => 9
                            ],
                            [
                                'name' => 'Dedication',
                                'level' => 10
                            ],
                            [
                                'name' => 'Principle',
                                'level' => 11
                            ],
                            [
                                'name' => 'Honesty',
                                'level' => 12
                            ],
                            [
                                'name' => 'Duty',
                                'level' => 13
                            ],
                            [
                                'name' => 'Homage',
                                'level' => 14
                            ],
                            [
                                'name' => 'Discipline',
                                'level' => 15
                            ],
                            [
                                'name' => 'Candor',
                                'level' => 16
                            ],
                            [
                                'name' => 'Integrity',
                                'level' => 17
                            ],
                            [
                                'name' => 'Loyalty',
                                'level' => 18
                            ],
                            [
                                'name' => 'Purity',
                                'level' => 19
                            ],
                            [
                                'name' => 'Virtue',
                                'level' => 20
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::PRESTIGE,
                        'spells' => [
                            [
                                'name' => 'Leverage',
                                'level' => 1
                            ],
                            [
                                'name' => 'Impact',
                                'level' => 2
                            ],
                            [
                                'name' => 'Clout',
                                'level' => 3
                            ],
                            [
                                'name' => 'Influence',
                                'level' => 4
                            ],
                            [
                                'name' => 'Direction',
                                'level' => 5
                            ],
                            [
                                'name' => 'Persuasion',
                                'level' => 6
                            ],
                            [
                                'name' => 'Jurisdiction',
                                'level' => 7
                            ],
                            [
                                'name' => 'Control',
                                'level' => 8
                            ],
                            [
                                'name' => 'Authority',
                                'level' => 9
                            ],
                            [
                                'name' => 'Prominence',
                                'level' => 10
                            ],
                            [
                                'name' => 'Superiority',
                                'level' => 11
                            ],
                            [
                                'name' => 'Dominance',
                                'level' => 12
                            ],
                            [
                                'name' => 'Primacy',
                                'level' => 13
                            ],
                            [
                                'name' => 'Command',
                                'level' => 14
                            ],
                            [
                                'name' => 'Ascendancy',
                                'level' => 15
                            ],
                            [
                                'name' => 'Leadership',
                                'level' => 16
                            ],
                            [
                                'name' => 'Supremacy',
                                'level' => 17
                            ],
                            [
                                'name' => 'Preeminence',
                                'level' => 18
                            ],
                            [
                                'name' => 'Kingsmanship',
                                'level' => 19
                            ],
                            [
                                'name' => 'Tyranny',
                                'level' => 20
                            ]
                        ]
                    ],
                    [
                        'name' => \App\MeasurableType::WRATH,
                        'spells' => [
                            [
                                'name' => 'Aggression',
                                'level' => 1
                            ],
                            [
                                'name' => 'Intimidation',
                                'level' => 2
                            ],
                            [
                                'name' => 'Frenzy',
                                'level' => 3
                            ],
                            [
                                'name' => 'Fear',
                                'level' => 4
                            ],
                            [
                                'name' => 'Hurt',
                                'level' => 5
                            ],
                            [
                                'name' => 'Rage',
                                'level' => 6
                            ],
                            [
                                'name' => 'Fierceness',
                                'level' => 7
                            ],
                            [
                                'name' => 'Violence',
                                'level' => 8
                            ],
                            [
                                'name' => 'Madness',
                                'level' => 9
                            ],
                            [
                                'name' => 'Anarchy',
                                'level' => 10
                            ],
                            [
                                'name' => 'Fury',
                                'level' => 11
                            ],
                            [
                                'name' => 'Reprisal',
                                'level' => 12
                            ],
                            [
                                'name' => 'Savagery',
                                'level' => 13
                            ],
                            [
                                'name' => 'Terror',
                                'level' => 14
                            ],
                            [
                                'name' => 'Hate',
                                'level' => 15
                            ],
                            [
                                'name' => 'Berserk',
                                'level' => 16
                            ],
                            [
                                'name' => 'Vengeance',
                                'level' => 17
                            ],
                            [
                                'name' => 'Horror',
                                'level' => 18
                            ],
                            [
                                'name' => 'Chaos',
                                'level' => 19
                            ],
                            [
                                'name' => 'Awe',
                                'level' => 20
                            ]
                        ]
                    ]
                ]
            ]
        ];


        foreach ( $measurableGroups as $group ) {
            foreach ( $group['types'] as $type ) {
                foreach ( $type['spells'] as $spellArray ) {

                    $spell = \App\Spell::create( [
                        'name' => $spellArray['name']
                    ] );

                    /** @var \App\Spell $spell */
                    $spell->measurableBoosts()->create([
                        'measurable_type_id' => $measurableTypes->where( 'name', $type['name'] )->first()->id,
                        'boost_level' => $spellArray['level']
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Spell::all()->each(function(\App\Spell $spell) {
            $spell->measurableBoosts()->delete();
            $spell->delete();
        });
    }
}
