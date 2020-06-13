<?php

return [
    'email' => env('ADMIN_EMAIL'),
    'slack' => [
        'webhook_url' => env('LOG_SLACK_WEBHOOK_URL')
    ]
];
