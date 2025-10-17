<?php
return [
    'Authentication' => [
        'guards' => [
            'Authentication.Session',
            'Authentication.Form' => [
                'fields' => [
                    'username' => 'username',   // column in users table
                    'password' => 'password',   // column in users table
                ],
                'loginUrl' => '/users/login',
            ],
        ],
    ],
];
