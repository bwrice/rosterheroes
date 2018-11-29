<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $football = \App\Sport::where('name','Football')->first();
        $baseball = \App\Sport::where('name', 'Baseball')->first();
        $basketball = \App\Sport::where('name', 'Basketball')->first();
        $hockey = \App\Sport::where('name', 'Hockey')->first();

        $sports = [
            [
                'sport' => $football,
                'teams' => [
                    [
                        'name' => 'Giants',
                        'location' => 'New York',
                        'abbreviation' => 'NYG'
                    ],
                    [
                        'name' => 'Eagles',
                        'location' => 'Philadelphia',
                        'abbreviation' => 'PHI'
                    ],
                    [
                        'name' => 'Redskins',
                        'location' => 'Washington',
                        'abbreviation' => 'WAS'
                    ],
                    [
                        'name' => 'Cowboys',
                        'location' => 'Dallas',
                        'abbreviation' => 'DAL'
                    ],
                    [
                        'name' => 'Saints',
                        'location' => 'New Orleans',
                        'abbreviation' => 'NO'
                    ],
                    [
                        'name' => 'Buccaneers',
                        'location' => 'Tampa Bay',
                        'abbreviation' => 'TB'
                    ],
                    [
                        'name' => 'Panthers',
                        'location' => 'Carolina',
                        'abbreviation' => 'CAR'
                    ],
                    [
                        'name' => 'Falcons',
                        'location' => 'Atlanta',
                        'abbreviation' => 'ATL'
                    ],
                    [
                        'name' => 'Packers',
                        'location' => 'Green Bay',
                        'abbreviation' => 'GB'
                    ],
                    [
                        'name' => 'Vikings',
                        'location' => 'Minnesota',
                        'abbreviation' => 'MIN'
                    ],
                    [
                        'name' => 'Bears',
                        'location' => 'Chicago',
                        'abbreviation' => 'CHI'
                    ],
                    [
                        'name' => 'Lions',
                        'location' => 'Detroit',
                        'abbreviation' => 'DET'
                    ],
                    [
                        'name' => 'Seahawks',
                        'location' => 'Seattle',
                        'abbreviation' => 'SEA'
                    ],
                    [
                        'name' => 'Cardinals',
                        'location' => 'Arizona',
                        'abbreviation' => 'ARI'
                    ],
                    [
                        'name' => 'Rams',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LAR'
                    ],
                    [
                        'name' => '49ers',
                        'location' => 'San Francisco',
                        'abbreviation' => 'SF'
                    ],
                    [
                        'name' => 'Patriots',
                        'location' => 'New England',
                        'abbreviation' => 'NE'
                    ],
                    [
                        'name' => 'Jets',
                        'location' => 'New York',
                        'abbreviation' => 'NYJ'
                    ],
                    [
                        'name' => 'Dolphins',
                        'location' => 'Miami',
                        'abbreviation' => 'MIA'
                    ],
                    [
                        'name' => 'Bills',
                        'location' => 'Buffalo',
                        'abbreviation' => 'BUF'
                    ],
                    [
                        'name' => 'Colts',
                        'location' => 'Indianapolis',
                        'abbreviation' => 'IND'
                    ],
                    [
                        'name' => 'Titans',
                        'location' => 'Tennessee',
                        'abbreviation' => 'TEN'
                    ],
                    [
                        'name' => 'Jaguars',
                        'location' => 'Jacksonville',
                        'abbreviation' => 'JAC'
                    ],
                    [
                        'name' => 'Texans',
                        'location' => 'Houston',
                        'abbreviation' => 'HOU'
                    ],
                    [
                        'name' => 'Ravens',
                        'location' => 'Baltimore',
                        'abbreviation' => 'BAL'
                    ],
                    [
                        'name' => 'Browns',
                        'location' => 'Cleveland',
                        'abbreviation' => 'CLE'
                    ],
                    [
                        'name' => 'Bengals',
                        'location' => 'Cincinnati',
                        'abbreviation' => 'CIN'
                    ],
                    [
                        'name' => 'Steelers',
                        'location' => 'Pittsburgh',
                        'abbreviation' => 'PIT'
                    ],
                    [
                        'name' => 'Broncos',
                        'location' => 'Denver',
                        'abbreviation' => 'DEN'
                    ],
                    [
                        'name' => 'Raiders',
                        'location' => 'Oakland',
                        'abbreviation' => 'OAK'
                    ],
                    [
                        'name' => 'Chiefs',
                        'location' => 'Kansas City',
                        'abbreviation' => 'KC'
                    ],
                    [
                        'name' => 'Chargers',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LAC'
                    ],
                ]
            ],
            [
                'sport' => $baseball,
                'teams' => [
                    [
                        'name' => 'Orioles',
                        'location' => 'Baltimore',
                        'abbreviation' => 'BAL'
                    ],
                    [
                        'name' => 'Yankees',
                        'location' => 'New York',
                        'abbreviation' => 'NYY'
                    ],
                    [
                        'name' => 'Blue Jays',
                        'location' => 'Toronto',
                        'abbreviation' => 'TOR'
                    ],
                    [
                        'name' => 'Red Sox',
                        'location' => 'Boston',
                        'abbreviation' => 'BOS'
                    ],
                    [
                        'name' => 'Rays',
                        'location' => 'Tampa Bay',
                        'abbreviation' => 'TB'
                    ],
                    [
                        'name' => 'White Sox',
                        'location' => 'Chicago',
                        'abbreviation' => 'CHW'
                    ],
                    [
                        'name' => 'Indians',
                        'location' => 'Cleveland',
                        'abbreviation' => 'CLE'
                    ],
                    [
                        'name' => 'Tigers',
                        'location' => 'Detroied',
                        'abbreviation' => 'DET'
                    ],
                    [
                        'name' => 'Royals',
                        'location' => 'Kansas City',
                        'abbreviation' => 'KC'
                    ],
                    [
                        'name' => 'Twins',
                        'location' => 'Minnesota',
                        'abbreviation' => 'MIN'
                    ],
                    [
                        'name' => 'Astros',
                        'location' => 'Houston',
                        'abbreviation' => 'HOU'
                    ],
                    [
                        'name' => 'Angels',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LAA'
                    ],
                    [
                        'name' => 'Athletics',
                        'location' => 'Oakland',
                        'abbreviation' => 'OAK'
                    ],
                    [
                        'name' => 'Mariners',
                        'location' => 'Seattle',
                        'abbreviation' => 'SEA'
                    ],
                    [
                        'name' => 'Rangers',
                        'location' => 'Texas',
                        'abbreviation' => 'TEX'
                    ],
                    [
                        'name' => 'Braves',
                        'location' => 'Atlanta',
                        'abbreviation' => 'ATL'
                    ],
                    [
                        'name' => 'Marlins',
                        'location' => 'Miami',
                        'abbreviation' => 'MIA'
                    ],
                    [
                        'name' => 'Mets',
                        'location' => 'New York',
                        'abbreviation' => 'NYM'
                    ],
                    [
                        'name' => 'Phillies',
                        'location' => 'Philadelphia',
                        'abbreviation' => 'PHI'
                    ],
                    [
                        'name' => 'Nationals',
                        'location' => 'Washington',
                        'abbreviation' => 'WAS'
                    ],
                    [
                        'name' => 'Cubs',
                        'location' => 'Chicago',
                        'abbreviation' => 'CHC'
                    ],
                    [
                        'name' => 'Reds',
                        'location' => 'Cincinnati',
                        'abbreviation' => 'CIN'
                    ],
                    [
                        'name' => 'Brewers',
                        'location' => 'Milwaukee',
                        'abbreviation' => 'MIL'
                    ],
                    [
                        'name' => 'Pirates',
                        'location' => 'Pittsburgh',
                        'abbreviation' => 'PIT'
                    ],
                    [
                        'name' => 'Cardinals',
                        'location' => 'St. Louis',
                        'abbreviation' => 'STL'
                    ],
                    [
                        'name' => 'Diamondbacks',
                        'location' => 'Arizona',
                        'abbreviation' => 'ARI'
                    ],
                    [
                        'name' => 'Rockies',
                        'location' => 'Colorado',
                        'abbreviation' => 'COL'
                    ],
                    [
                        'name' => 'Dodgers',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LAD'
                    ],
                    [
                        'name' => 'Padres',
                        'location' => 'San Diego',
                        'abbreviation' => 'SD'
                    ],
                    [
                        'name' => 'Giants',
                        'location' => 'San Francisco',
                        'abbreviation' => 'SF'
                    ],
                ]
            ],
            [
                'sport' => $basketball,
                'teams' => [
                    [
                        'name' => 'Celtics',
                        'location' => 'Boston',
                        'abbreviation' => 'BOS'
                    ],
                    [
                        'name' => 'Nets',
                        'location' => 'Brooklyn',
                        'abbreviation' => 'BKN'
                    ],
                    [
                        'name' => 'New York',
                        'location' => 'Knicks',
                        'abbreviation' => 'NY'
                    ],
                    [
                        'name' => '76ers',
                        'location' => 'Philadelphia',
                        'abbreviation' => 'PHI'
                    ],
                    [
                        'name' => 'Raptors',
                        'location' => 'Toronto',
                        'abbreviation' => 'TOR'
                    ],
                    [
                        'name' => 'Bulls',
                        'location' => 'Chicago',
                        'abbreviation' => 'CHI'
                    ],
                    [
                        'name' => 'Cavaliers',
                        'location' => 'Cleveland',
                        'abbreviation' => 'CLE'
                    ],
                    [
                        'name' => 'Pistons',
                        'location' => 'Detroit',
                        'abbreviation' => 'DET'
                    ],
                    [
                        'name' => 'Pacers',
                        'location' => 'Indiana',
                        'abbreviation' => 'IND'
                    ],
                    [
                        'name' => 'Bucks',
                        'location' => 'Milwaukee',
                        'abbreviation' => 'MIL'
                    ],
                    [
                        'name' => 'Hawks',
                        'location' => 'Atlanta',
                        'abbreviation' => 'ATL'
                    ],
                    [
                        'name' => 'Hornets',
                        'location' => 'Charlotte',
                        'abbreviation' => 'CHA'
                    ],
                    [
                        'name' => 'Heat',
                        'location' => 'Miami',
                        'abbreviation' => 'MIA'
                    ],
                    [
                        'name' => 'Magic',
                        'location' => 'Orlando',
                        'abbreviation' => 'ORL'
                    ],
                    [
                        'name' => 'Wizards',
                        'location' => 'Washington',
                        'abbreviation' => 'WAS'
                    ],
                    [
                        'name' => 'Warriors',
                        'location' => 'Golden State',
                        'abbreviation' => 'GS'
                    ],
                    [
                        'name' => 'Clippers',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LAC'
                    ],
                    [
                        'name' => 'Lakers',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LAL'
                    ],
                    [
                        'name' => 'Suns',
                        'location' => 'Phoenix',
                        'abbreviation' => 'PHO'
                    ],
                    [
                        'name' => 'Kings',
                        'location' => 'Sacramento',
                        'abbreviation' => 'SAC'
                    ],
                    [
                        'name' => 'Mavericks',
                        'location' => 'Dallas',
                        'abbreviation' => 'DAL'
                    ],
                    [
                        'name' => 'Rockets',
                        'location' => 'Houston',
                        'abbreviation' => 'HOU'
                    ],
                    [
                        'name' => 'Grizzlies',
                        'location' => 'Memphis',
                        'abbreviation' => 'MEM'
                    ],
                    [
                        'name' => 'Pelicans',
                        'location' => 'New Orleans',
                        'abbreviation' => 'NO'
                    ],
                    [
                        'name' => 'Spurs',
                        'location' => 'San Antonio',
                        'abbreviation' => 'SA'
                    ],
                    [
                        'name' => 'Nuggets',
                        'location' => 'Denver',
                        'abbreviation' => 'DEN'
                    ],
                    [
                        'name' => 'Timberwolves',
                        'location' => 'Minnesota',
                        'abbreviation' => 'MIN'
                    ],
                    [
                        'name' => 'Thunder',
                        'location' => 'Oklahoma City',
                        'abbreviation' => 'OKC'
                    ],
                    [
                        'name' => 'Trail Blazers',
                        'location' => 'Portland',
                        'abbreviation' => 'POR'
                    ],
                    [
                        'name' => 'Jazz',
                        'location' => 'Utah',
                        'abbreviation' => 'UTH'
                    ],
                ]
            ],
            [
                'sport' => $hockey,
                'teams' => [
                    [
                        'name' => 'Bruins',
                        'location' => 'Boston',
                        'abbreviation' => 'BOS'
                    ],
                    [
                        'name' => 'Sabres',
                        'location' => 'Buffalo',
                        'abbreviation' => 'BUF'
                    ],
                    [
                        'name' => 'Red Wings',
                        'location' => 'Detroit',
                        'abbreviation' => 'DET'
                    ],
                    [
                        'name' => 'Panthers',
                        'location' => 'Florida',
                        'abbreviation' => 'FLO'
                    ],
                    [
                        'name' => 'Canadiens',
                        'location' => 'Montreal',
                        'abbreviation' => 'MON'
                    ],
                    [
                        'name' => 'Senators',
                        'location' => 'Ottawa',
                        'abbreviation' => 'OTT'
                    ],
                    [
                        'name' => 'Lightning',
                        'location' => 'Tampa Bay',
                        'abbreviation' => 'TB'
                    ],
                    [
                        'name' => 'Maple Leafs',
                        'location' => 'Toronto',
                        'abbreviation' => 'TOR'
                    ],
                    [
                        'name' => 'Hurricanes',
                        'location' => 'Carolina',
                        'abbreviation' => 'CAR'
                    ],
                    [
                        'name' => 'Blue Jackets',
                        'location' => 'Columnbus',
                        'abbreviation' => 'CLB'
                    ],
                    [
                        'name' => 'Devils',
                        'location' => 'New Jersey',
                        'abbreviation' => 'NJ'
                    ],
                    [
                        'name' => 'Islanders',
                        'location' => 'New York',
                        'abbreviation' => 'NYI'
                    ],
                    [
                        'name' => 'Rangers',
                        'location' => 'New York',
                        'abbreviation' => 'NYR'
                    ],
                    [
                        'name' => 'Flyers',
                        'location' => 'Philadelphia',
                        'abbreviation' => 'PHI'
                    ],
                    [
                        'name' => 'Penguins',
                        'location' => 'Pittsburgh',
                        'abbreviation' => 'PIT'
                    ],
                    [
                        'name' => 'Capitals',
                        'location' => 'Washington',
                        'abbreviation' => 'WAS'
                    ],
                    [
                        'name' => 'Blackhawks',
                        'location' => 'Chicago',
                        'abbreviation' => 'CHI'
                    ],
                    [
                        'name' => 'Avalanche',
                        'location' => 'Colorado',
                        'abbreviation' => 'COL'
                    ],
                    [
                        'name' => 'Stars',
                        'location' => 'Dallas',
                        'abbreviation' => 'DAL'
                    ],
                    [
                        'name' => 'Wild',
                        'location' => 'Minnesota',
                        'abbreviation' => 'MIN'
                    ],
                    [
                        'name' => 'Predators',
                        'location' => 'Nashville',
                        'abbreviation' => 'NAS'
                    ],
                    [
                        'name' => 'Blues',
                        'location' => 'St. Louis',
                        'abbreviation' => 'STL'
                    ],
                    [
                        'name' => 'Jets',
                        'location' => 'Winnipeg',
                        'abbreviation' => 'WIN'
                    ],
                    [
                        'name' => 'Ducks',
                        'location' => 'Anaheim',
                        'abbreviation' => 'ANA'
                    ],
                    [
                        'name' => 'Coyotes',
                        'location' => 'Arizona',
                        'abbreviation' => 'ARI'
                    ],
                    [
                        'name' => 'Flames',
                        'location' => 'Calgary',
                        'abbreviation' => 'CAL'
                    ],
                    [
                        'name' => 'Oilers',
                        'location' => 'Edmonton',
                        'abbreviation' => 'EDM'
                    ],
                    [
                        'name' => 'Kings',
                        'location' => 'Los Angeles',
                        'abbreviation' => 'LA'
                    ],
                    [
                        'name' => 'Sharks',
                        'location' => 'San Jose',
                        'abbreviation' => 'SJ'
                    ],
                    [
                        'name' => 'Canucks',
                        'location' => 'Vancouver',
                        'abbreviation' => 'VAN'
                    ],
                ]
            ]
        ];

        \App\Team::unguard();

        foreach ($sports as $sport) {
            foreach ($sport['teams'] as $team) {
                \App\Team::create([
                    'sport_id' => $sport['sport']->id,
                    'name' => $team['name'],
                    'location' => $team['location'],
                    'abbreviation' => $team['abbreviation']
                ]);
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
        \App\Team::query()->delete();
    }
}
