<?php

return [
    'driver' => env('STATS_INTEGRATION', \App\External\Stats\MySportsFeed\MySportsFeed::ENV_KEY),

    'fake_stats' => [
        'start_date' => env('FAKE_STATS_START', '04-01-2020')
    ]
];
