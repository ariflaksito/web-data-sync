<?php
return [
    'determineRouteBeforeAppMiddleware' => false,
    'outputBuffering' => false,
    'tokenExpired' => '+1 hour',
    'displayErrorDetails' => true,
    'db' => [
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'database'  => 'amsosv2',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ]
];