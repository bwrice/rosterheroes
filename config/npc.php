<?php

if (! function_exists('getDefaultNPCConfig')) {
    function getDefaultNPCConfig() {
        return [
            'squads' => [
                [
                    'name' => 'Sunguards',
                    'heroes' => [
                        'Adnalor',
                        'Valzeroz',
                        'Herximorg',
                        'Gilfinora',
                        'Bryberonx',
                        'Rogidok'
                    ]
                ],
                [
                    'name' => 'Chardro',
                    'heroes' => [
                        'Oakencoat',
                        'Graymane',
                        'Bone Stalker',
                        'Beastbane',
                        'Marchik'
                    ]
                ],
                [
                    'name' => 'jemar0z',
                    'heroes' => [
                        'jemar0z 1',
                        'jemar0z 2',
                        'jemar0z 3',
                        'jemar0z 4',
                        'jemar0z 5',
                        'jemar0z 6',
                        'jemar0z 7'
                    ]
                ],
                [
                    'name' => 'Surprises',
                    'heroes' => [
                        'Boom',
                        'Blam',
                        'Plop',
                        'Crash',
                        'Bang',
                        'Smack'
                    ]
                ],
                [
                    'name' => 'Dead Presidents',
                    'heroes' => [
                        'Washington',
                        'Lincoln',
                        'Jefferson',
                        'Roosevelt',
                        'Kennedy',
                        'Reagan'
                    ]
                ],
                [
                    'name' => 'Knights of Elm',
                    'heroes' => [
                        'Perren Storm',
                        'Salam Thunder',
                        'Mal Buckley',
                        'Colbey Stan',
                        'Haiko Brax'
                    ]
                ],
                [
                    'name' => 'Mike n Ikes',
                    'heroes' => [
                        'Mike J',
                        'Mike R',
                        'Ike Z',
                        'Ike B',
                        'Ike C',
                        'Mike T',
                        'Mike D',
                        'Ike F'
                    ]
                ],
                [
                    'name' => 'F C Shartz',
                    'heroes' => [
                        'Stormwhacker',
                        'Fireburner',
                        'Thundermaker',
                        'Icebreaker',
                        'LavaScorcher'
                    ]
                ],
                [
                    'name' => 'Rock and Roll',
                    'heroes' => [
                        'Led Zeppelin',
                        'The Beatles',
                        'Rolling Stones',
                        'Queen',
                        'AC DC',
                        'The Eagles',
                        'Aerosmith',
                        'Queen'
                    ]
                ],
                [
                    'name' => 'Battalarz',
                    'heroes' => [
                        'Ronnie Lawz',
                        'Jairo Dayz',
                        'Max Puncherz',
                        'Vern Bladez',
                        'Phil Wardz',
                        'Lothar Thorz'
                    ]
                ],
                [
                    'name' => 'Best of Italy',
                    'heroes' => [
                        'Gaagii',
                        'Buetaluom',
                        'Nidzati',
                        'Tokedu',
                        'Yontel',
                        'Atliv'
                    ]
                ],
                [
                    'name' => 'Metalswords',
                    'heroes' => [
                        'Jeroma Dagger',
                        'Earl Blade',
                        'Anton Angle',
                        'Norwin Sharp',
                        'Rigby Cutter',
                        'Xavier Slice'
                    ]
                ],
                [
                    'name' => 'Shimmer Shields',
                    'heroes' => [
                        'Gregor Might',
                        'Luka Power',
                        'Amery Strength',
                        'Alonzo Fear',
                        'Gavyn Brute',
                        'Maxim Might'
                    ]
                ],
                [
                    'name' => 'Yells of Wild',
                    'heroes' => [
                        'Wrenchcloak',
                        'Catspine',
                        'WiggleSmith',
                        'Quicknight',
                        'Mechaspark',
                        'Geartrench'
                    ]
                ],
                [
                    'name' => 's1Toons',
                    'heroes' => [
                        'b1smort',
                        'herb99',
                        'lootking101',
                        'templar88',
                        'shyzar72',
                        'h12345'
                    ]
                ],
            ],
        ];
    }
}


return [
    'development' => getDefaultNPCConfig(),
    'local' => getDefaultNPCConfig(),
    'beta' => getDefaultNPCConfig(),
    'production' => [
        'squads' => [
            [
                'name' => 'The Smiths',
                'heroes' => [
                    'Bob Smith',
                    'Dave Smith',
                    'John Smith',
                    'Carl Smith',
                    'Jeff Smith',
                    'Will Smith',
                    'Kevin Smith'
                ]
            ],
        ]
    ],
    'user' => [
        'email' => env('NPC_USER_EMAIL'),
        'password' => env('NPC_USER_PASSWORD')
    ]
];