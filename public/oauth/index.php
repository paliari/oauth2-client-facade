<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$credentials = [
    'google' => [
        'id'     => '308314076135-eiisl3krsqn4u4q7df65e6q678hu0a6v.apps.googleusercontent.com',
        'secret' => 'pKUYc60yngqH7UqDd4PL6-hi',
        'scopes' => ['userinfo_email', 'userinfo_profile']
    ],
    'github' => [
        'id'     => 'b47e69b7b2861ea7c357',
        'secret' => '617b233b8d0a8223d3c70ccb85f7b4397af78f94',
        'scopes' => []
    ],
    'twitter' => [
        'id'     => 'bjU7Jx0aaLF43txIlbVpPg',
        'secret' => 'DXy1ryNgbma16QjV5xPkreskY6uZLpf1gWlzxbUKA',
        'scopes' => []
    ]
];

$controller = new \Paliari\Oauth2ClientFacade\Controller($credentials);
$controller->run();
