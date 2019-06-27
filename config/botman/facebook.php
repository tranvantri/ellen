<?php

return [

    'token' => env('FACEBOOK_TOKEN'),

    'app_secret' => env('FACEBOOK_APP_SECRET'),

    'verification' => env('FACEBOOK_VERIFICATION'),

    'start_button_payload' => 'GET_STARTED',

    'greeting_text' => [
        'greeting' => [
            [
                'locale' => 'default',
                'text' => 'Hello!',
            ],
            [
                'locale' => 'en_US',
                'text' => 'Timeless apparel for the masses.',
            ],
        ],
    ],

    'persistent_menu' => [
        [
            'locale' => 'default',
            'composer_input_disabled' => 'true',
            'call_to_actions' => [
                [
                    'title' => 'My Account',
                    'type' => 'nested',
                    'call_to_actions' => [
                        [
                            'title' => 'Pay Bill',
                            'type' => 'postback',
                            'payload' => 'PAYBILL_PAYLOAD',
                        ],
                    ],
                ],
                [
                    'type' => 'web_url',
                    'title' => 'Latest News',
                    'url' => 'http://botman.io',
                    'webview_height_ratio' => 'full',
                ],
            ],
        ],
    ],

    'whitelisted_domains' => [
        'https://petersfancyapparel.com',
        'https://ellen.dev/',
    ],
];
