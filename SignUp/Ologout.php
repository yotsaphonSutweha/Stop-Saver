<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/Ops.php';
require_once __DIR__ . '/../Operations/connection.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();


$accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired",
time()-3600, '/', getenv('COOKIE_DOMAIN'));
redirect('/login.php', ['cookies' => [$accessToken]]);