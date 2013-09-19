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
    ],
    'IsseHom' => [
        'id'     => '4a266d6734390f606cc19a252ee9d741b8ec70b1',
        'secret' => '2f491fb06b889c55d787580e432e12a6de12ef11',
        'scopes' => []
    ],
    'IssePro' => [
        'id'     => '4a266d6734390f606cc19a252ee9d741b8ec70b1',
        'secret' => '2f491fb06b889c55d787580e432e12a6de12ef11',
        'scopes' => []
    ]
];

$controller = new \Paliari\Oauth2ClientFacade\Controller($credentials);
$controller->run();
